<?php

declare(strict_types=1);

namespace App\Test\HttpClient;

use ArrayIterator;
use Symfony\Component\HttpClient\MockHttpClient as SymfonyMockHttpClient;

class MockHttpClient extends SymfonyMockHttpClient
{
    private ArrayIterator $iterator;

    public function __construct()
    {
        $this->iterator = new ArrayIterator();

        parent::__construct($this->iterator);
    }

    public function setResponseFactory(array $responses): void
    {
        foreach ($responses as $response) {
            $this->iterator->append($response);
        }
    }
}
