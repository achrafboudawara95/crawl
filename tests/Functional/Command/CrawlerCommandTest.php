<?php

namespace App\Tests\Functional\Command;

use App\Test\CommandKernelTestCase;
use App\Test\Traits\LoadMockTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CrawlerCommandTest extends CommandKernelTestCase
{
    use LoadMockTrait;

    protected static $commandName = 'app:crawl';
    public function testExecute()
    {

        $responses = [
            new MockResponse($this->loadMock('news.json'), ['http_code' => 200]),
        ];
        $client = self::getContainer()->get(HttpClientInterface::class);
        $client->setResponseFactory($responses);

        // execute the command
        $this->commandTester->execute([]);

        // assert the output
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Finished with success', $output);
    }
}
