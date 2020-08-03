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

    public function testSignInOkDefault(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret', 'realm' => 'default'];
        $this->json()->request('POST', '/app/token', $credentials);
        $this->assertResponseIsSuccessful();
    }

    public function testSignInOkApp0(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret0', 'realm' => 'app0'];
        $this->json()->request('POST', '/app/token', $credentials);
        $this->assertResponseIsSuccessful();
    }

    public function testSignInBadCredentials(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'badpass', 'realm' => 'default'];
        $this->json()->request('POST', '/app/token', $credentials);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testSignInBadRealm(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret', 'realm' => 'app0'];
        $this->json()->request('POST', '/app/token', $credentials);
        $this->assertResponseStatusCodeSame(401);
    }
}
