<?php

namespace App\Tests\Dto;

use App\Tests\Utils\ApiUtilsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VersionTest extends WebTestCase
{
    use ApiUtilsTrait;

    public function testVersionShouldSuccess(): void {
        $this->json()->request('GET', '/public/versions/current');
        $this->assertResponseIsSuccessful();
    }
}
