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
            '/sadmin/applications',
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
        }

    }

    public function testSuperadminCanDisableAccountShouldWork(): void{

        $params = [
            'enabled'   => false
        ];

        $resp = $this->request('PUT', '/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859002', $params);

        $content = json_decode($resp->getContent(),true);
        self::assertResponseIsSuccessful();

        self::assertEquals(false, $content['enabled']);


    }

    public function testSuperadminCanDisableUserShouldWork(): void{

        $params = [
            'enabled'   => false
        ];

        $resp = $this->request('PUT', '/user/users/AD779175-76D1-466A-99BF-536AA3F5E003', $params);

        $content = json_decode($resp->getContent(),true);
        self::assertResponseIsSuccessful();

        self::assertEquals(false, $content['enabled']);


    }
}
