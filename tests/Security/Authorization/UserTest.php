<?php

namespace App\Tests\Security\Authorization;

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

    public function testUserListEntitiesShouldDeny(): void {
        $uris = [
            '/admin/users',
            '/sadmin/accounts',
            '/sadmin/permissions',
            '/sadmin/applications'
        ];

        foreach ($uris as $uri) {
            $this->request('GET', $uri);
            self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }
    }


    public function testUserCanRetrieveItself(): void {
        $id = "AD779175-76D1-466A-99BF-536AA3F5E002";
        $this->request('GET', "/user/users/$id");
        self::assertResponseIsSuccessful();
    }

    public function testUserCannotRetrieveOthers(): void {
        $id = "AD779175-76D1-466A-99BF-536AA3F5E000";
        $this->request('GET', "/user/users/$id");
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }


    public function testSuperdminCanDeleteSelfAccount(): void {
        $this->request('DELETE', '/user/accounts/05E88714-8FB3-46B0-893D-97CBCA859002');
        self::assertResponseIsSuccessful();
    }

}
