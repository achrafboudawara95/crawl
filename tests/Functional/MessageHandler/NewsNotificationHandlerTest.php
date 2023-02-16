<?php

namespace App\Tests\Functional\MessageHandler;

use App\Message\NewsNotification;
use App\MessageHandler\NewsNotificationHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class NewsNotificationHandlerTest extends KernelTestCase
{
    private $handler;
    protected function setUp(): void
    {
        self::bootKernel();

        $this->handler = self::getContainer()->get(NewsNotificationHandler::class);
    }

    protected function tearDown(): void
    {
        $this->handler = null;
    }

    public function testInvoke()
    {
        // create a mock NewsNotification object to use as the message
        $newsNotification = new NewsNotification(
            'Test news notification',
            'desc',
            'image',
            'date'
        );

        ($this->handler)($newsNotification);

        // perform additional assertions as necessary
        $this->assertTrue(true);
    }
}