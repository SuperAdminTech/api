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

    public function testSendMessageToUser(): void {
        $this->login('admin@example.com');
        $this->json()->request(
            'POST',
            '/admin/messages',
            [
                'username' => 'test@example.com',
                'subject' => 'test subject',
                'body' => 'test body'
            ]
        );
        $this->assertResponseIsSuccessful();
    }

    public function testSendMessageToUserFromApiKey(): void {
        $this->headers(['HTTP_X-Auth-Key' => 'key_admin']);
        $this->json()->request(
            'POST',
            '/admin/messages',
            [
                'username' => 'test@example.com',
                'subject' => 'test subject',
                'body' => 'test body'
            ]
        );
        $this->assertResponseIsSuccessful();
    }

    public function testSendMessageToUserFromApiKeyUserShouldFail(): void {
        $this->headers(['HTTP_X-Auth-Key' => 'key_test']);
        $this->json()->request(
            'POST',
            '/admin/messages',
            [
                'username' => 'test@example.com',
                'subject' => 'test subject',
                'body' => 'test body'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
