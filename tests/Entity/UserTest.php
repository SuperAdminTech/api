<?php

namespace App\Tests\Entity;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;


    public function testListUsersFromAdminShouldReturnUsersInsideApplication(){
        $this->login('admin@recogeme.com', 'secret', 'recogeme');
        $resp = $this->request('GET', '/admin/users');

        self::assertResponseIsSuccessful();

        $content = json_decode($resp->getContent(),true);
        $users = $content['hydra:member'];
        foreach ($users as $user){
            self::assertEquals('f557d0fc-1421-4d47-9f84-8a30cafe939e', $user['application']['id']);
        }
    }

    public function testListUsersFromUserForbidden(){
        $this->login();
        $this->request('GET', '/admin/users');

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testGetSingleUserInSameApplicationShouldWork(){
        $this->login('admin@example.com');
        $this->request('GET', '/user/users/AD779175-76D1-466A-99BF-536AA3F5E002');

        self::assertResponseIsSuccessful();

    }

    public function testGetSingleUserInOtherApplicationNotFound(){
        $this->login('admin@example.com');
        $this->request('GET', '/user/users/3f3f83aa-ec38-41fa-a0d6-2b7b4fec769d');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

    }

    public function testUpdateUserInSameApplicationShouldWork(){
        $params = [
            'username' => 'updated@example.com'
        ];

        $this->login('admin@example.com');
        $response = $this->request('PUT', '/user/users/AD779175-76D1-466A-99BF-536AA3F5E002', $params);

        self::assertResponseIsSuccessful();

        $user = json_decode($response->getContent(), true);

        self::assertEquals('updated@example.com', $user['username']);

    }

    public function testUpdateUserRoleWithInventedRoleShouldFail(){
        $params = [
            'roles' => ['ROLE_INVENTED']
        ];

        $this->login('admin@example.com');
        $this->request('PUT', '/user/users/AD779175-76D1-466A-99BF-536AA3F5E002', $params);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    public function testUpdateUserInOtherApplicationShouldFail(){
        $params = [
            'username' => 'updated@example.com'
        ];

        $this->login('admin@example.com');
        $this->request('PUT', '/user/users/3f3f83aa-ec38-41fa-a0d6-2b7b4fec769d', $params);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

    }

    public function testCreateUserInsideAccountShouldWork(){
        $params = [
            'username' => 'test@testexample.com',
            'account' => '/user/accounts/b2598d13-41fa-4de6-a1ff-641d8ddf26d0',
            'application' => '/admin/applications/f557d0fc-1421-4d47-9f84-8a30cafe939e',
            'permissions' => [ 'ACCOUNT_MANAGER' ],
            'roles' => [ 'ROLE_USER' ]
        ];
        $this->login('admin@recogeme.com', 'secret', 'recogeme');
        $this->json()->request('POST', '/admin/users', $params);

        $this->assertResponseIsSuccessful();
    }

    public function testCreateUserInsideAccountFromNonAdminShouldFail(){
        $params = [
            'username' => 'test@testexample.com',
            'account' => '/user/accounts/b2598d13-41fa-4de6-a1ff-641d8ddf26d0',
            'application' => '/admin/applications/f557d0fc-1421-4d47-9f84-8a30cafe939e',
            'permissions' => [ 'ACCOUNT_MANAGER' ],
            'roles' => [ 'ROLE_USER' ]
        ];
        $this->login('test@example.com', 'secret', 'recogeme');
        $this->json()->request('POST', '/admin/users', $params);

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testCreateUserInsideAccountFromOtherAdminAccountShouldFail(){
        $params = [
            'username' => 'test@testexample.com',
            'account' => '/user/accounts/b2598d13-41fa-4de6-a1ff-641d8ddf26d0',
            'application' => '/admin/applications/05E88714-8FB3-46B0-893D-97CBCA859001',
            'permissions' => [ 'ACCOUNT_MANAGER' ],
            'roles' => [ 'ROLE_USER' ]
        ];
        $this->login('admin@recogeme.com', 'secret', 'recogeme');
        $this->json()->request('POST', '/admin/users', $params);

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

}