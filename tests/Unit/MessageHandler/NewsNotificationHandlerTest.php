<?php

namespace App\Tests\Unit\MessageHandler;

use App\Message\NewsNotification;
use App\MessageHandler\NewsNotificationHandler;
use PHPUnit\Framework\TestCase;

class NewsNotificationHandlerTest extends TestCase
{
    private ?NewsNotificationHandler $handler;
    protected function setUp(): void
    {
        $this->handler = new NewsNotificationHandler();
    }

    protected function tearDown(): void
    {
        $this->handler = null;
    }

    public function testInvoke()
    {
        $message = new NewsNotification(
            'Test news notification',
            'desc',
            'image',
            'date'
        );

        $result = ($this->handler)($message);

        $this->assertNull($result);
    }
}