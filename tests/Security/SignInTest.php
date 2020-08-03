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

    public function testSignInOkDefaultShouldSuccess(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret', 'realm' => 'default'];
        $this->json()->request('POST', '/app/token', $credentials);
        $this->assertResponseIsSuccessful();
    }

    public function testSignInOkDefaultShouldReturnToken(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret', 'realm' => 'default'];
        $resp = $this->json()->request('POST', '/app/token', $credentials);
        $this->assertObjectHasAttribute('token', json_decode($resp->getContent()));
    }

    public function testSignInOkApp0ShouldSuccess(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret0', 'realm' => 'app0'];
        $this->json()->request('POST', '/app/token', $credentials);
        $this->assertResponseIsSuccessful();
    }

    public function testSignInBadCredentialsShouldFail(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'badpass', 'realm' => 'default'];
        $this->json()->request('POST', '/app/token', $credentials);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testSignInBadRealmShouldFail(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret', 'realm' => 'app0'];
        $this->json()->request('POST', '/app/token', $credentials);
        $this->assertResponseStatusCodeSame(401);
    }
}
