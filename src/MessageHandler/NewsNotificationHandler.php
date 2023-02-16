<?php

namespace App\MessageHandler;

use App\Message\NewsNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewsNotificationHandler implements MessageHandlerInterface
{
    public function __invoke(NewsNotification $message)
    {

    }
}