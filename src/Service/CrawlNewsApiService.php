<?php

namespace App\Service;

use App\Exception\CrawlException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CrawlNewsApiService extends CrawlService
{
    private string $apiKey;
    private string $country;
    public function __construct(
        HttpClientInterface $client,
        string $sourceURL,
        string $apiKey,
        string $country)
    {
        parent::__construct($client, $sourceURL);

        $this->apiKey = $apiKey;
        $this->country = $country;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getData(): array
    {
        $response = $this->httpClient->request('GET', $this->sourceURL, [
            'query' => [
                'country' => $this->country,
                'apiKey' => $this->apiKey
            ]
        ]);

        try {
            $content = $response->getContent();
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            throw new CrawlException(sprintf("unable to crawl %s", $this->sourceURL));
        }

        return $this->parseData($content);
    }

    private function parseData(string $content): array
    {
        $data = json_decode($content);
        return [];
    }
}