<?php

namespace App\Tests\Command;

use App\Tests\Utils\ApiUtilsTrait;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AccountsRemoveNonValidatedTest extends WebTestCase
{
    use RecreateDatabaseTrait;
    use ApiUtilsTrait;

    public function testExecuteCommand(){
        $kernel = $this->getApiClient()->getKernel();

        $application = new Application($kernel);

        $command = $application->find('app:accounts:remove:non-validated');

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertEquals(0, $commandTester->getStatusCode());

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('2 users', $output);
        $this->assertStringContainsString('1 accounts', $output);
        $this->assertStringContainsString('Done', $output);


    }
}