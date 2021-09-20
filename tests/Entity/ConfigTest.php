<?php

namespace App\Tests\Entity;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConfigTest extends WebTestCase
{

    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function testGetConfigsFromSuperShouldWork(){
        $this->login('super@example.com');
        $this->json()->request('GET','/admin/configs/4ea0ba7c-85d4-4b2a-ab62-3deee8bcca46');

        $this->assertResponseIsSuccessful();
    }
}