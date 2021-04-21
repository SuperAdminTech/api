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
            $this->assertEquals($application_id, $account['application']['id']);
        }
    }

    public function testGetAccountsFromAdminShouldReturnOnlyAccountsInHisApplication(){
        $this->login('admin@apps2_3.com', 'secret', 'app2');
        $resp = $this->json()->request('GET', '/admin/accounts');

        $this->assertResponseIsSuccessful();

        $content = json_decode($resp->getContent(),true);
        $accounts = $content['hydra:member'];

        foreach ($accounts as $account){
            self::assertEquals('5df09443-8f43-4992-bc1b-075e300af61f', $account['application']['id']);
        }


    }
}