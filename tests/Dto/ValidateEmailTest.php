<?php

namespace App\Tests\Dto;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ValidateEmailTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function testEmailVerificationShouldSuccess(): void {
        $this->json()->request(
            'PUT',
            '/public/users/AD779175-76D1-466A-99BF-536AA3F5E005/verify',
            ['code' => '096a7868-9b59-4fcb-88a2-d6a4476066a7']
        );
        $this->assertResponseIsSuccessful();
    }
}
