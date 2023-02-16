<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class CrawlService implements CrawlInterface
{
    protected HttpClientInterface $httpClient;
    protected string $sourceURL;

    public function __construct(HttpClientInterface $client, string $sourceURL)
    {
        $this->httpClient = $client;
        $this->sourceURL = $sourceURL;
    }
}