<?php

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class CrawlService implements CrawlInterface
{
    protected HttpClientInterface $httpClient;
    protected string $sourceURL;

    protected Crawler $crawler;

    public function __construct(HttpClientInterface $client, Crawler $crawler, string $sourceURL)
    {
        $this->httpClient = $client;
        $this->sourceURL = $sourceURL;
        $this->crawler = $crawler;
    }
}