<?php

namespace App\Tests\Functional\Command;

use App\Test\CommandKernelTestCase;

class CrawlerCommandTest extends CommandKernelTestCase
{
    protected static $commandName = 'app:crawl';

    public function testExecute()
    {
        // execute the command
        $this->commandTester->execute([]);

        // assert the output
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Finished with success', $output);
    }
}
