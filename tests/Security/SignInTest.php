<?php

namespace App\Tests\Security;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SignInTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function testSignInOkDefaultShouldSuccess(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret', 'realm' => 'default'];
        $this->json()->request('POST', '/public/token', $credentials);
        $this->assertResponseIsSuccessful();
    }

    public function testSignInOkDefaultShouldReturnToken(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret', 'realm' => 'default'];
        $resp = $this->json()->request('POST', '/public/token', $credentials);
        $this->assertObjectHasAttribute('token', json_decode($resp->getContent()));
    }

    public function testTokenHasRequiredInfo(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret', 'realm' => 'default'];
        $resp = $this->json()->request('POST', '/public/token', $credentials);
        $token = json_decode($resp->getContent())->token;
        $decoded = self::decodeJWT($token);
        self::assertObjectHasAttribute("username", $decoded);
        self::assertObjectHasAttribute("ip", $decoded);
        self::assertObjectHasAttribute("application", $decoded);
        self::assertObjectHasAttribute("permissions", $decoded);
        self::assertIsArray($decoded->permissions);
        self::assertGreaterThan(0, count($decoded->permissions));
        self::assertObjectHasAttribute('grants', $decoded->permissions[0]);
        self::assertObjectHasAttribute('account', $decoded->permissions[0]);
    }

    private static function decodeJWT($token){
        return json_decode(base64_decode(explode(".", $token)[1]));
    }

    public function testSignInOkApp0ShouldSuccess(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret', 'realm' => 'app0'];
        $this->json()->request('POST', '/public/token', $credentials);
        $this->assertResponseIsSuccessful();
    }

    public function testSignInBadCredentialsShouldFail(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'badpass', 'realm' => 'default'];
        $this->json()->request('POST', '/public/token', $credentials);
        $this->assertResponseStatusCodeSame(401);
    }

    public function testSignInBadRealmShouldFail(): void {
        $credentials = ['username' => 'test@example.com', 'password' => 'secret', 'realm' => 'app1'];
        $this->json()->request('POST', '/public/token', $credentials);
        $this->assertResponseStatusCodeSame(401);
    }
}
