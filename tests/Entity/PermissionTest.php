<?php

namespace App\Tests\Entity;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PermissionTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function testUpdatePermissionsFromSuperadminShouldWork(){
        $this->login('super@example.com');
        $resp = $this->json()->request('PUT', '/user/permissions/5b4f0d06-566f-43e9-90c6-9c490712929a', ['grants' => ['ACCOUNT_WORKER']]);
        $this->assertResponseIsSuccessful();
        $content = json_decode($resp->getContent(),true);

        self::assertEquals(['ACCOUNT_WORKER'],  $content['grants']);
    }

    public function testUpdatePermissionsFromAdminInSameApplicationShouldWork(){
        $this->login('admin@recogeme.com', 'secret', 'recogeme');
        $resp = $this->json()->request('PUT', '/user/permissions/5b4f0d06-566f-43e9-90c6-9c490712929a', ['grants' => ['ACCOUNT_WORKER']]);
        $this->assertResponseIsSuccessful();
        $content = json_decode($resp->getContent(),true);

        self::assertEquals(['ACCOUNT_WORKER'],  $content['grants']);
    }

    public function testUpdatePermissionsFromAdminInOtherApplicationShouldFail(){
        $this->login('admin@recogeme.com', 'secret', 'recogeme');
        $this->json()->request('PUT', '/user/permissions/5c701159-7d7d-4a68-8de4-6feb17039ec7', ['grants' => ['ACCOUNT_WORKER']]);
        self::assertResponseStatusCodeSame(403);
    }

    public function testListPermissionsFromSuperShouldWork(){
        $this->login('super@example.com');
        $resp = $this->json()->request('GET', '/sadmin/permissions');

        self::assertResponseIsSuccessful();

    }

    public function testDeletePermissionsFromAdminShouldWork(){
        $this->login('admin@recogeme.com', 'secret', 'recogeme');
        $this->json()->request('DELETE', '/user/permissions/5b4f0d06-566f-43e9-90c6-9c490712929a');
        self::assertResponseIsSuccessful();
    }

    public function testDeletePermissionsFromManagerInSameApplicationShouldWork(){
        $this->login('test@example.com', 'secret', 'recogeme');
        $this->json()->request('DELETE', '/user/permissions/5b4f0d06-566f-43e9-90c6-9c490712929a');
        self::assertResponseIsSuccessful();
    }

    public function testDeletePermissionsFromManagerInOtherApplicationShouldFail(){
        $this->login('test@example.com', 'secret', 'recogeme');
        $this->json()->request('DELETE', '/user/permissions/05E88714-8FB3-46B0-893D-97CBCA859002');
        self::assertResponseStatusCodeSame(403);
    }
}