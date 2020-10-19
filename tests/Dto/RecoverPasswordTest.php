<?php

namespace App\Tests\Dto;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecoverPasswordTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;
    
    public function testRecoverPasswordShouldSuccess(): void {
        $this->json()->request(
            'PUT',
            '/public/users/AD779175-76D1-466A-99BF-536AA3F5E005/recover',
            [
                'code' => '096a7868-9b59-4fcb-88a2-d6a4476066a8',
                'password' => 'newpass'
            ]
        );
        $this->assertResponseIsSuccessful();
    }
}
