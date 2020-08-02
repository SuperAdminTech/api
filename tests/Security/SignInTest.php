<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Tests\Utils\ApiUtilsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SignInTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function testSignInOk(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret', 'realm' => 'default'];
        //$credentials = ['username' => 'test@example.com', 'password' => 'secret'];
        $this->json()->request('POST', '/app/token', $credentials);
        $this->assertResponseIsSuccessful();
    }
}
