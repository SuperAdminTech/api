<?php

namespace App\Tests\Dto;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RecoverPasswordRequestTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;
    
    public function testRecoverPasswordRequestShouldSuccess(): void {
        $this->json()->request(
            'POST',
            '/public/users/recover',
            [
                'username' => 'test@example.com',
                'realm' => 'default'
            ]
        );
        $this->assertResponseIsSuccessful();
    }

    public function testRecoverPasswordRequestWithoutRealmShouldFail(): void {
        $this->json()->request(
            'POST',
            '/public/users/recover',
            ['username' => 'test@example.com']
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testRecoverPasswordRequestWithUnexistentUsernameShouldFail(): void {
        $this->json()->request(
            'POST',
            '/public/users/recover',
            [
                'username' => 'test1234@example.com',
                'realm' => 'default'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
