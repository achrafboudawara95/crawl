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
    public function testInvoke()
    {
        self::bootKernel();
        // create a mock NewsNotification object to use as the message
        $newsNotification = new NewsNotification(
            'Test news notification',
            'desc',
            'image',
            'date'
        );

        $handler = self::getContainer()->get(NewsNotificationHandler::class);

        $handler($newsNotification);

        // perform additional assertions as necessary
        $this->assertTrue(true);
    }
}