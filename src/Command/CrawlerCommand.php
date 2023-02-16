<?php

namespace App\Command;

use App\Message\NewsNotification;
use App\Service\CrawlInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CrawlerCommand extends Command
{
    protected static $defaultName = 'app:crawl';
    protected static $defaultDescription = 'Create a news from external source.';
    private CrawlInterface $crawlService;
    private MessageBusInterface $bus;

    public function __construct(CrawlInterface $crawlService, MessageBusInterface $bus, string $name = null)
    {
        parent::__construct($name);
        $this->crawlService = $crawlService;
        $this->bus = $bus;
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you crawl news from internet...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $news = $this->crawlService->getData();

        /** @var NewsNotification $new */
        foreach ($news as $new){
            $this->bus->dispatch($new);
        }

        $output->writeln("Finished with success");
        return Command::SUCCESS;
    }
}