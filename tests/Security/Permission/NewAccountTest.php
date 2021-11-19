<?php

namespace App\Tests\Security\Permission;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class NewAccountTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function setUp(): void {
        $this->json()->login('test@example.com');
    }

    public function testUserHaveRequiredInfo(): void {
        $id = "AD779175-76D1-466A-99BF-536AA3F5E002";
        $resp = $this->request('GET', "/user/users/$id");
        self::assertResponseIsSuccessful();
        $user = json_decode($resp->getContent());
        $this->markTestIncomplete();
        //We should check the required fields here
        //before we were checking fields inside permissions but permissions now is an IRI
    }

    public function testUserCreatesNewAccountSuccessfully(){
        $this->request('POST', '/user/accounts', ['name' => 'my new account', 'realm' => 'default']);
        self::assertResponseIsSuccessful();
    }

    public function testUserCreatesNewAccountAlreadyTaken(){
        $this->request('POST', '/user/accounts', ['name' => 'account_admin', 'realm' => 'default']);
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
