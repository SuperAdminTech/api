<?php

namespace App\Tests\Entity;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AccountTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function testGetAccounts(){
        $this->login('super@example.com');
        $this->json()->request('GET', '/admin/accounts');
        $this->assertResponseIsSuccessful();

        $this->login('admin@example.com');
        $this->json()->request('GET', '/admin/accounts');
        $this->assertResponseIsSuccessful();

        $this->login('test@example.com');
        $this->json()->request('GET', '/admin/accounts');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

    }

    public function testGetAccountsFilteredByApplication(){
        $this->login('super@example.com');
        $application_id = '05E88714-8FB3-46B0-893D-97CBCA859000';
        $resp = $this->json()->request('GET', '/admin/accounts?application.id=' . $application_id);
        $this->assertResponseIsSuccessful();

        $content = json_decode($resp->getContent(), true);
        $accounts = $content['hydra:member'];
        foreach ($accounts as $account){
            $this->assertArrayHasKey('application', $account);
            $this->assertEquals('/admin/applications/'.$application_id, $account['application']);
        }
    }

    public function testGetAccountsFromAdminShouldReturnOnlyOwnedAccounts(){
        //this test works also with app3 in realm
        $this->login('admin@apps2_3.com', 'secret', 'app2');
        $resp = $this->json()->request('GET', '/admin/accounts');

        $this->assertResponseIsSuccessful();

        $content = json_decode($resp->getContent(),true);

        self::assertEquals(2, $content['hydra:totalItems']);

    }

    public function testGetAccountsFromAdminFilteredByApplicationShouldWork(){
        //this test works also with app3 in realm
        $this->login('admin@apps2_3.com', 'secret', 'app2');
        $resp = $this->json()->request('GET', '/admin/accounts?application.id=89afcec8-1f8f-4993-9b13-fab2037287f2');

        $this->assertResponseIsSuccessful();

        $content = json_decode($resp->getContent(),true);

        self::assertEquals(1, $content['hydra:totalItems']);

        $accounts = $content['hydra:member'];

        foreach ($accounts as $account){
            self::assertEquals('/admin/applications/89afcec8-1f8f-4993-9b13-fab2037287f2', $account['application']);
        }

    }

    public function testGetAccountsFromAdminInRecogemeShouldWork(){
        $this->login('admin@recogeme.com', 'secret', 'recogeme');
        $resp = $this->json()->request('GET', '/admin/accounts');

        $this->assertResponseIsSuccessful();

        $content = json_decode($resp->getContent(),true);

        self::assertEquals(2, $content['hydra:totalItems']);
    }

    public function testUpdateAccountFromAdminInRecogemeShouldWork(){
        $this->login('admin@recogeme.com', 'secret', 'recogeme');
        $params = [
            'name' => 'testname'
        ];

        $resp = $this->json()->request('PUT', '/user/accounts/11a1ff76-0eec-4975-b5b4-a9dd4bf87d61', $params);

        self::assertResponseIsSuccessful();

        $content = json_decode($resp->getContent(),true);
        self::assertEquals('testname' ,$content['name']);
    }

    public function testDeleteAccountFromAdminInRecogemeShouldWork(){
        $this->login('admin@recogeme.com', 'secret', 'recogeme');

        $this->json()->request('DELETE', '/user/accounts/11a1ff76-0eec-4975-b5b4-a9dd4bf87d61');

        self::assertResponseIsSuccessful();

    }

    public function testDeleteAccountFromAdminInOtherAppShouldFail(){
        $this->login('admin@recogeme.com', 'secret', 'recogeme');

        $this->json()->request('DELETE', '/user/accounts/40235439-fbb2-428a-80e5-99300b1f1b77');

        self::assertResponseStatusCodeSame(404);

    }
}