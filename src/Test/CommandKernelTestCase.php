<?php

declare(strict_types=1);

namespace App\Test;

use App\Command\CrawlerCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CommandKernelTestCase extends KernelTestCase
{
    protected ?Command $command;
    protected ?CommandTester $commandTester;
    protected static $commandName = 'app:command-kernel-test-case';

    protected function setUp(): void
    {
        $kernel = static::bootKernel();
        $application = new Application($kernel);
        $this->command = $application->find(static::$commandName);
        $this->commandTester = new CommandTester($this->command);
    }

    protected function tearDown(): void
    {
        $this->commandTester = null;
    }
}
