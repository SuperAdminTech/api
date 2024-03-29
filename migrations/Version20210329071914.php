<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Account;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329071914 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription() : string
    {
        return 'Add enabled to accounts and set enabled=true';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account ADD enabled TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP enabled');
    }

    public function preUp(Schema $schema): void
    {
        parent::preUp($schema);
        $this->runCommand([
            'command' => 'app:database:dump',
            'name'  =>  'Version20210329071914.sql'
        ]);

    }

    public function postUp(Schema $schema): void
    {
        parent::postUp($schema);

        $em = $this->container->get('doctrine.orm.entity_manager');
        $aRepo = $em->getRepository(Account::class);

        foreach ($aRepo->findAll() as $account){
            $account->enabled = true;
        }
        $em->flush();


    }

    private function runCommand(array $commandSpec){
        /** @var KernelInterface $kernel */
        $kernel = $this->container->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        return $application->run(
            new ArrayInput($commandSpec),
            new NullOutput()
        );

    }
}
