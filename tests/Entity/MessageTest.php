<?php

namespace App\Tests\Entity;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MessageTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function testSendMessageToUserAccount(): void {
        $this->login('admin@example.com');
        $this->json()->request(
            'POST',
            '/admin/messages',
            [
                'account' => '/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859001',
                'subject' => 'test subject',
                'body' => 'test body'
            ]
        );
        $this->assertResponseIsSuccessful();
    }

    public function testSendMessageToUserAccountFromApiKey(): void {
        $this->headers(['HTTP_X-Auth-Key' => 'key_admin']);
        $this->json()->request(
            'POST',
            '/admin/messages',
            [
                'account' => '/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859001',
                'subject' => 'test subject',
                'body' => 'test body'
            ]
        );
        $this->assertResponseIsSuccessful();
    }

    public function testSendMessageToUserAccountFromApiKeyUserShouldFail(): void {
        $this->headers(['HTTP_X-Auth-Key' => 'key_test']);
        $this->json()->request(
            'POST',
            '/admin/messages',
            [
                'account' => '/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859001',
                'subject' => 'test subject',
                'body' => 'test body'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
