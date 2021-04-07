<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Filesystem\Filesystem;

class DatabaseDumpCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected static $defaultName = 'app:database:dump';
    protected static $defaultDescription = 'Makes a database dump or restore database from dump';

    /** @var OutputInterface */
    private $output;

    /** @var InputInterface */
    private $input;

    private $database;
    private $username;
    private $password;
    private $host;
    private $path;

    /** filesystem utility */
    private $fs;

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the sql file')
            ->addOption('restore', null, InputOption::VALUE_NONE, 'Restore database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $databaseUrl = $this->container->getParameter('database_url') ;
        $parsedUrl = parse_url($databaseUrl);

        $this->database = str_replace('/', '', $parsedUrl['path']);
        $this->username = $parsedUrl['user'];
        $this->password = $parsedUrl['pass'];
        $this->host = $parsedUrl['host'];
        $this->path = '/tmp/'.$input->getArgument('name');
        $this->fs = new Filesystem();

        if($input->getOption('restore')){
            $this->output->writeln(sprintf('<comment>Restoring <fg=green>%s</fg=green> from <fg=green>%s</fg=green> </comment>', $this->database, $this->path ));
            $this->restoreDatabase();
            $output->writeln('<comment>Restore database done.</comment>');
        }else{
            $this->output->writeln(sprintf('<comment>Dumping <fg=green>%s</fg=green> to <fg=green>%s</fg=green> </comment>', $this->database, $this->path ));
            $this->createDirectoryIfRequired();
            $this->dumpDatabase();
            $output->writeln('<comment>Back up done.</comment>');
        }

        return 0;
    }

    private function createDirectoryIfRequired() {
        if (! $this->fs->exists($this->path)){
            $this->fs->mkdir(dirname($this->path));
        }
    }

    private function dumpDatabase()
    {
        $cmd = sprintf('mysqldump --opt --skip-add-locks --skip-comments -B %s -h %s -u %s --password=%s > %s', // > %s'
            $this->database,
            $this->host,
            $this->username,
            $this->password,
            $this->path
        );

        $result = $this->runCommand($cmd);

        if($result['exit_status'] > 0) {
            throw new \Exception('Could not dump database: ' . var_export($result['output'], true));
        }

        //$this->fs->dumpFile($this->path, $result['output']);
    }

    private function restoreDatabase()
    {
        $cmd = sprintf('mysql -h %s -u %s --password=%s < %s',
            $this->host,
            $this->username,
            $this->password,
            $this->path
        );

        $result = $this->runCommand($cmd);
        if($result['exit_status'] > 0){
            throw new \Exception('Could not restore database: ' . var_export($result['output'], true));
        }
    }

    /**
     * Runs a system command, returns the output
     *
     * @param $command
     * @return array
     */
    protected function runCommand($command):array
    {
        $command .=" >&1";
        exec($command, $output, $exit_status);
        return array(
            "output"      => $output,
            "exit_status" => $exit_status
        );
    }
}
