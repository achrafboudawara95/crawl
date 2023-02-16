<?php

namespace App\Tests\Unit\Command;

use App\Message\NewsNotification;
use PHPUnit\Framework\TestCase;
use App\Command\CrawlerCommand;
use App\Service\CrawlInterface;
use stdClass;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class CrawlerCommandTest extends TestCase
{
    private $crawlService;
    private $bus;

    public function setUp(): void
    {
        $this->crawlService = $this->createMock(CrawlInterface::class);
        $this->bus = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function tearDown(): void
    {
        $this->crawlService = null;
        $this->bus = null;
    }

    public function testExecute()
    {
        $command = new CrawlerCommand($this->crawlService, $this->bus);
        $commandTester = new CommandTester($command);
        $envelope = new Envelope(new stdClass());

        $this->crawlService->expects($this->once())
            ->method('getData')
            ->willReturn([
                new NewsNotification('Breaking news!', 'desc', 'img', 'date'),
                new NewsNotification('More news!', 'desc', 'img', 'date')
            ]);

        $this->bus->expects($this->exactly(2))
            ->method('dispatch')
            ->with(
                $this->isInstanceOf(NewsNotification::class)
            )
            ->willReturn($envelope);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertEquals("Finished with success\n", $output);
    }
}
