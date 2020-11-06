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
        $this->request('POST', '/user/permissions', $params);
        self::assertResponseIsSuccessful();
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
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
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

}
