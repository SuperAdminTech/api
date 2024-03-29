<?php

namespace App\Tests\Security\Authorization;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function setUp(): void {
        $this->json()->login('admin@example.com');
    }

    public function testAdminListEntitiesShouldSuccess(): void {
        $uris = [
            '/admin/accounts',
        ];

        foreach ($uris as $uri){
            $this->request('GET', $uri);
            self::assertResponseIsSuccessful();
        }
    }

    public function testAdminListEntitiesShouldForbid(): void {
        $uris = [
            '/sadmin/users',
            '/sadmin/permissions',
        ];

        foreach ($uris as $uri){
            $this->request('GET', $uri);
            self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }
    }

    public function testAdminCanDisableAccountInSameApplicationShouldWork(): void{

        $params = [
            'enabled'   => false
        ];

        $resp = $this->request('PUT', '/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859002', $params);

        $content = json_decode($resp->getContent(),true);
        self::assertResponseIsSuccessful();

        self::assertEquals(false, $content['enabled']);

    }

    public function testAdminCanDisableAccountInOtherApplicationShouldFail(): void{

        $params = [
            'enabled'   => false
        ];

        $this->request('PUT', '/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859003', $params);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testAdminCanDisableAccountSelfShouldFail(): void{

        $params = [
            'enabled'   => false
        ];

        $this->request('PUT', '/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859001', $params);

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

    }

    public function testAdminDeleteAccountShouldWork(){
        $this->json()->login('admin@example.com');

        $this->request('DELETE', '/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859004');
        self::assertResponseIsSuccessful();

    }

    public function testAdminDeleteApiKeyShouldWork(){
        $this->json()->login('admin@example.com');

        $this->request('DELETE', '/admin/api_keys/29d54cc7-814b-45a6-9a46-51cb2a85ab62');
        self::assertResponseIsSuccessful();

    }

    public function testAdminDeleteApplicationForbid(){
        $this->json()->login('admin@example.com');

        $this->request('DELETE', '/sadmin/applications/05E88714-8FB3-46B0-893D-97CBCA859000');
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

    }

    public function testAdminDeletePermissionForbid(){
        $this->json()->login('admin@example.com');

        $this->request('DELETE', '/user/permissions/22e625ac-fbe3-4635-b301-ef22beabee22');
        self::assertResponseIsSuccessful();

    }

    public function testAdminDeleteUserForbid(){
        $this->json()->login('admin@example.com');

        $this->request('DELETE', '/sadmin/users/AD779175-76D1-466A-99BF-536AA3F5E004');
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

    }

}
