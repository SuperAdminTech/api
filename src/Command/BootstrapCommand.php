<?php

namespace App\Command;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Permission;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BootstrapCommand extends Command
{
    protected static $defaultName = 'app:bootstrap';
    protected static $defaultDescription = 'Creates first user and application';

    protected function configure()
    {
        $this->setDescription(self::$defaultDescription);
    }

    private EntityManagerInterface $em;

    /**
     * BootstrapCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->em = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->text("Creating first user and application");

        list($username, $password) = [uniqid('admin'), uniqid('secret')];


        $io->text("Creating Application");
        $app = new Application();
        $app->name = "Default Application";
        $app->realm = "default";
        $this->em->persist($app);
        $io->text("Name: {$app->name}");
        $io->text("Realm: {$app->realm}");
        $io->success("OK");

        $io->text("Creating User");
        $user = new User();
        $user->username = $username;
        $user->plain_password = $password;
        $user->roles = ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN'];
        $user->email_validated = true;
        $this->em->persist($user);
        $io->text("Username: {$username}");
        $io->text("Password: {$password}");
        $io->success("OK");

        $io->text("Creating Account");
        $account = new Account();
        $account->name = $user->username;
        $account->application = $app;
        $this->em->persist($account);
        $io->text("Name: {$account->name}");
        $io->success("OK");

        $io->text("Creating Permission");
        $perm = new Permission();
        $perm->grants = [Permission::ACCOUNT_MANAGER];
        $perm->account = $account;
        $perm->user = $user;
        $this->em->persist($perm);
        $io->text("Grants: [{$perm->grants[0]}]");
        $io->success("OK");

        $this->em->flush();

        $io->success('SUCCESS');

        return 0;
    }

}
