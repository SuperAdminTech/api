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

}