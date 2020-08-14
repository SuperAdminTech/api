<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Tests\Utils\ApiUtilsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function setUp(): void {
        $this->json()->login('test@example.com', 'secret');
    }

    public function testUserListUsersShouldDeny(): void {
        $this->request('GET', '/users');
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testUserListApplicationsShouldDeny(): void {
        $this->request('GET', '/applications');
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}
