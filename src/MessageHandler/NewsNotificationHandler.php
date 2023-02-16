<?php

namespace App\MessageHandler;

use App\Entity\News;
use App\Message\NewsNotification;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewsNotificationHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(NewsNotification $message)
    {
        $news = new News();
        $news->setTitle($message->getTitle());
        $news->setDescription($message->getDescription());
        $news->setImage($message->getImage());
        $news->setDate(DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $message->getDate()));

        $this->entityManager->persist($news);
        $this->entityManager->flush();
    }
}