<?php

namespace App\Tests\Action;

use App\Entity\User;
use App\Tests\Utils\ApiUtilsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterActionTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function testSignUpOk(): void {
        $credentials = ['username' => 'test@test.com', 'password' => '1234', 'app_token' => 'default'];
        $this->json()->request('POST', '/app/sign_up', $credentials);
        $this->assertResponseIsSuccessful();
    }

    public function testSignUpWithoutAppToken(): void {
        $credentials = ['username' => 'test.com', 'password' => '1234'];
        $this->json()->request('POST', '/app/sign_up', $credentials);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testSignUpAlreadyRegistered(): void {
        /** @var EntityManagerInterface $em */
        $em = static::createClient()->getContainer()->get('doctrine.orm.entity_manager');
        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy([]);
        self::assertNotNull($user);
        $credentials = ['username' => $user->username, 'password' => '1234', 'app_token' => 'default'];
        $response = $this->json()->request('POST', '/app/sign_up', $credentials);
        static::assertGreaterThanOrEqual(400, $response->getStatusCode());
        static::assertLessThan(500, $response->getStatusCode());
    }
}
