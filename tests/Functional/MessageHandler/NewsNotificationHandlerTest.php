<?php

namespace App\Tests\Functional\MessageHandler;

use App\Entity\News;
use App\Message\NewsNotification;
use App\MessageHandler\NewsNotificationHandler;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewsNotificationHandlerTest extends KernelTestCase
{
    private $handler;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->handler = self::getContainer()->get(NewsNotificationHandler::class);
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        $this->handler = null;
    }

    public function testInvoke()
    {
        /** @var NewsRepository $newsRepository */
        $newsRepository = $this->entityManager->getRepository(News::class);
        // create a mock NewsNotification object to use as the message
        $newsNotification = new NewsNotification(
            'Test news notification',
            'desc',
            'image',
            '2023-02-16T05:37:55Z'
        );

        ($this->handler)($newsNotification);

        $news = $newsRepository->findOneBy(['title' => $newsNotification->getTitle()]);

        $this->assertInstanceOf(News::class, $news);
    }
}