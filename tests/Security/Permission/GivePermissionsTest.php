<?php

namespace App\Tests\Security\Permission;

use App\Entity\Permission;
use App\Entity\User;
use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GivePermissionsTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    protected function setUp(): void {
        $this->json()->login('test@example.com');
    }

    public function testUserGivesPermissionSuccessfully(){
        $params = [
            'account' => "/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859002",
            'username' => 'super@example.com',
            'grants' => [Permission::ACCOUNT_WORKER]
        ];
        $resp = $this->request('POST', '/user/permissions', $params);
        self::assertResponseIsSuccessful();
        $content = json_decode($resp->getContent(),true);
        self::assertEquals($content['account']['application']['@id'], $content['user']['application']);
    }

    public function testUserGivesWrongPermissionShouldFail(){
        $params = [
            'account' => "/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859002",
            'username' => 'super@example.com',
            'grants' => [Permission::ACCOUNT_WORKER, 'ACCOUNT_INVALID']
        ];
        $this->request('POST', '/user/permissions', $params);
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testUserGivesPermissionAlreadyAllowed(){
        $params = [
            'account' => "/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859002",
            'username' => 'test@example.com',
            'grants' => [Permission::ACCOUNT_WORKER]
        ];
        $this->request('POST', '/user/permissions', $params);
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUserGivesPermissionForbidden(){
        $params = [
            'account' => "/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859000",
            'username' => 'admin@example.com',
            'grants' => [Permission::ACCOUNT_WORKER]
        ];
        $this->request('POST', '/user/permissions', $params);
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    //TODO check this test because is not asigning permision to user, is that right?
    public function testSuperAdminGivesPermissionsSuccessfully(){
        $this->json()->login('super@example.com');
        $params = [
            'account' => "/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859002",
            'username' => 'test@example.com',
            'grants' => [Permission::ACCOUNT_WORKER]
        ];
        $this->request('POST', '/sadmin/permissions', $params);
        self::assertResponseIsSuccessful();
    }

    public function testUserGivesPermissionInOtherAppShouldFail(){

        $this->markTestIncomplete();
        $params = [
            'account' => "/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859002",
            'username' => 'default_app@example.com',
            'grants' => [Permission::ACCOUNT_WORKER]
        ];
        $resp = $this->request('POST', '/user/permissions', $params);

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

    }
}
