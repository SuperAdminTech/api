<?php

namespace App\Tests\Dto;

use App\Entity\User;
use App\Tests\Utils\ApiUtilsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SignUpTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function testSignUpOkShouldSuccess(): void {
        $credentials = ['username' => 'unused@example.com', 'password' => '1234', 'realm' => 'default'];
        $this->json()->request('POST', '/public/users', $credentials);
        $this->assertResponseIsSuccessful();
    }

    public function testSignUpWithoutAppTokenShouldFail(): void {
        $credentials = ['username' => 'test.com', 'password' => '1234'];
        $this->json()->request('POST', '/public/users', $credentials);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testSignUpAlreadyRegisteredShouldFail(): void {
        /** @var EntityManagerInterface $em */
        $em = $this->createApiClient()->getContainer()->get('doctrine.orm.entity_manager');
        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy([]);
        self::assertNotNull($user);
        $credentials = ['username' => $user->username, 'password' => '1234', 'realm' => 'default'];
        $resp = $this->json()->request('POST', '/public/users', $credentials);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $err = json_decode($resp->getContent())->{'hydra:description'};
        self::assertEquals("Username already taken.", $err);
    }

    public function testSignUpAlreadyRegisteredInOtherAppShouldSuccess(): void {
        /** @var EntityManagerInterface $em */
        $em = $this->createApiClient()->getContainer()->get('doctrine.orm.entity_manager');
        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy([]);
        self::assertNotNull($user);
        $credentials = ['username' => $user->username, 'password' => '1234', 'realm' => 'app1'];
        $this->json()->request('POST', '/public/users', $credentials);
        $this->assertResponseIsSuccessful();
    }

}
