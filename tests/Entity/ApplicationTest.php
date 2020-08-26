<?php

namespace App\Tests\Entity;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApplicationTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function testSuperadminCanCreateApplications(): void {
        $this->login('super@example.com');
        $this->json()->request('POST', '/applications', ['name' => 'test', 'realm' => 'test']);
        $this->assertResponseIsSuccessful();
    }

    public function testAdminCannotCreateApplications(): void {
        $this->login('admin@example.com');
        $this->json()->request('POST', '/applications', ['name' => 'test', 'realm' => 'test']);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testUserCannotCreateApplications(): void {
        $this->login('test@example.com');
        $this->json()->request('POST', '/applications', ['name' => 'test', 'realm' => 'test']);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }



}
