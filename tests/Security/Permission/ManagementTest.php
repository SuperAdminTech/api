<?php

namespace App\Tests\Security\Permission;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ManagementTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function setUp(): void {
        $this->json()->login('test@example.com', 'secret');
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

}
