<?php

namespace App\Tests\Security\Permission;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ManagementTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function testUserHaveRequiredInfo(): void {
        $this->json()->login('test@example.com', 'secret');
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

    public function testUserCreatesNewAccount(){
        $this->json()->login('test@example.com', 'secret');
        $this->request('POST', '/user/new_account', ['name' => 'my new account']);
        self::assertResponseIsSuccessful();
    }

}
