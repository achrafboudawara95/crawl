<?php

namespace App\Tests\Unit\MessageHandler;

use App\Message\NewsNotification;
use App\MessageHandler\NewsNotificationHandler;
use PHPUnit\Framework\TestCase;

class NewsNotificationHandlerTest extends TestCase
{
    public function testInvoke()
    {
        $message = new NewsNotification(
            'Test news notification',
            'desc',
            'image',
            'date'
        );
        $handler = new NewsNotificationHandler();

        $result = $handler($message);

        $this->assertNull($result);
    }
}