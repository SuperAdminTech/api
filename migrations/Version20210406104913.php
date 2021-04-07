<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\User;
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
final class Version20210406104913 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription() : string
    {
        return 'Relate users with application';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD application_id CHAR(36) COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493E030ACD FOREIGN KEY (application_id) REFERENCES application (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6493E030ACD ON user (application_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493E030ACD');
        $this->addSql('DROP INDEX IDX_8D93D6493E030ACD ON user');
        $this->addSql('ALTER TABLE user DROP application_id');
    }

    public function preUp(Schema $schema): void
    {
        parent::preUp($schema);
        $this->runCommand([
            'command' => 'app:database:dump',
            'name'  => 'Version20210406104913.sql'
        ]);
    }

    public function postUp(Schema $schema): void
    {
        parent::postUp($schema);

        $em = $this->container->get('doctrine.orm.entity_manager');
        $uRepo = $em->getRepository(User::class);

        /** @var User $user */
        foreach ($uRepo->findAll() as $user){
            $permissions = $user->permissions;
            foreach ($permissions as $permission){
                $account = $permission->account;
                $application = $account->application;
                if($application){
                    $user->application = $application;
                }
            }
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
