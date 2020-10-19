<?php

namespace App\Tests\Dto;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
}
