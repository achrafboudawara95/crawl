<?php

declare(strict_types=1);

namespace App\Test\HttpClient;

use ArrayIterator;
use Symfony\Component\HttpClient\MockHttpClient as SymfonyMockHttpClient;

class MockHttpClient extends SymfonyMockHttpClient
{
    public function setResponses($responses): void
    {
        $iterator = new ArrayIterator();

        foreach ($responses as $response) {
            $iterator->append($response);
        }

        $this->setResponseFactory($iterator);
    }
}
