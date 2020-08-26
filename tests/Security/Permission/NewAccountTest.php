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
        $resp = $this->request('GET', "/users/$id");
        self::assertResponseIsSuccessful();
        $user = json_decode($resp->getContent());
        foreach ($user->permissions as $permission){
            self::assertEquals('Permission', $permission->{'@type'});
            self::assertObjectHasAttribute('user', $permission);
            self::assertObjectHasAttribute('account', $permission);
            self::assertObjectHasAttribute('grants', $permission);
        }
    }

    public function testUserCreatesNewAccountSuccessfully(){
        $this->request('POST', '/accounts/new', ['name' => 'my new account']);
        self::assertResponseIsSuccessful();
    }

    public function testUserCreatesNewAccountAlreadyTaken(){
        $this->request('POST', '/accounts/new', ['name' => 'account_admin']);
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}