<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class Version20210326123213
 * @package DoctrineMigrations
 */
final class Version20210326123213 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription() : string
    {
        return 'Add enabled to users and set enabled=true';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD enabled TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP enabled');
    }

    public function postUp(Schema $schema): void
    {
        parent::postUp($schema);
        $em = $this->container->get('doctrine.orm.entity_manager');
        $uRepo = $em->getRepository(User::class);

        foreach ($uRepo->findAll() as $user){
            $user->enabled = true;
        }

        $em->flush();

    }

}
