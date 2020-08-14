<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Tests\Utils\ApiUtilsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function setUp(): void {
        $this->json()->login('admin@example.com', 'secret');
    }

    public function testAdminListAllEntitiesShouldSuccess(): void {
        $uris = [
            '/users',
            '/accounts',
            '/applications',
            '/permissions',
        ];

        foreach ($uris as $uri){
            $this->request('GET', $uri);
            self::assertResponseIsSuccessful();
        }
    }

}
