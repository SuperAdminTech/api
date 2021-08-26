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
        $this->json()->request('POST', '/sadmin/applications', ['name' => 'test', 'realm' => 'test']);
        $this->assertResponseIsSuccessful();
    }

    public function testAdminCannotCreateApplications(): void {
        $this->login('admin@example.com');
        $this->json()->request('POST', '/sadmin/applications', ['name' => 'test', 'realm' => 'test']);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testUserCannotCreateApplications(): void {
        $this->login();
        $this->json()->request('POST', '/sadmin/applications', ['name' => 'test', 'realm' => 'test']);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminGetApplication(){
        $this->login('admin@apps2_3.com', 'secret', 'app2');
        $resp = $this->request('GET', 'admin/applications/5df09443-8f43-4992-bc1b-075e300af61f');

        self::assertResponseIsSuccessful();
    }

    public function testAdminGetOtherApplication(){
        $this->login('admin@apps2_3.com', 'secret', 'app2');
        $this->request('GET', 'admin/applications/05E88714-8FB3-46B0-893D-97CBCA859000');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testSuperListApplications(){
        $this->login('super@example.com');
        $resp = $this->request('GET', 'sadmin/applications');
        self::assertResponseIsSuccessful();
        $content = json_decode($resp->getContent(),true);

        self::assertGreaterThanOrEqual(6, $content['hydra:totalItems']);

    }

    public function testAdminListApplications(){
        $this->login('admin@example.com');
        $resp = $this->request('GET', 'admin/applications');
        self::assertResponseIsSuccessful();
        $content = json_decode($resp->getContent(),true);
        self::assertEquals(1, $content['hydra:totalItems']);

    }

}
