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
            ['username' => 'test@example.com']
        );
        $this->assertResponseIsSuccessful();
    }

    public function testRecoverPasswordRequestWithUnexistentUsernameShouldFail(): void {
        $this->json()->request(
            'POST',
            '/public/users/recover',
            ['username' => 'test1234@example.com']
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
