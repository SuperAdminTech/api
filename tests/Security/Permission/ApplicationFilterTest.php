<?php

namespace App\Tests\Security\Permission;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationFilterTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function setUp(): void {
        $this->json()->login('admin@example.com');
    }

    public function testAdminOnlySeesSameApplicationAccounts(): void {
        $resp = $this->request('GET', "/admin/accounts");
        self::assertResponseIsSuccessful();
        $content = json_decode($resp->getContent());
        $accounts = $content->{'hydra:member'};
        self::assertGreaterThan(0, count($accounts));
        $app0 = $accounts[0]->application;
        foreach ($accounts as $account) {
            self::assertEquals($app0->id, $account->application->id);
        }
    }
}
