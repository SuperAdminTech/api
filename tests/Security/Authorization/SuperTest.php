<?php

namespace App\Tests\Security\Authorization;

use App\Entity\User;
use App\Tests\Utils\ApiUtilsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SuperTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function setUp(): void {
        $this->json()->login('super@example.com');
    }

    public function testSuperdminListEntitiesShouldSuccess(): void {
        $uris = [
            '/admin/applications',
            '/admin/accounts',
            '/sadmin/users',
            '/sadmin/permissions',
        ];

        foreach ($uris as $uri){
            $this->request('GET', $uri);
            self::assertResponseIsSuccessful();
        }
    }

    public function testSuperdminCanDeleteSelfAccount(): void {
        $this->request('DELETE', '/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859000');
        self::assertResponseIsSuccessful();
    }

    public function testSuperadminListUsersContainApplication(): void{
        $application_id = '05E88714-8FB3-46B0-893D-97CBCA859001';
        $resp = $this->request('GET', '/sadmin/users?permissions.account.application.id='.$application_id);
        self::assertResponseIsSuccessful();

        $content = json_decode($resp->getContent(), true);
        $users = $content['hydra:member'];
        self::assertGreaterThan(0, $content['hydra:totalItems']);
        foreach ($users as $user){
            self::assertArrayHasKey('permissions', $user);
            $permissions = $user['permissions'];
            self::assertArrayHasKey('account', $permissions[0]);
            $account = $permissions[0]['account'];
            self::assertArrayHasKey('application', $account);
            $application = $account['application'];
            self::assertEquals($application_id, $application['id']);
        }

    }
}
