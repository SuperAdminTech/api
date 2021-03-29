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

}
