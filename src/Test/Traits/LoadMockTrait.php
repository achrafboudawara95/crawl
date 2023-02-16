<?php

namespace App\Test\Traits;

use RuntimeException;

trait LoadMockTrait
{
    protected function loadMock(string $path): string
    {
        $path = __DIR__.'/../../../mocks/'.$path;
        if (!file_exists($path)) {
            throw new RuntimeException('Failed to load '.$path);
        }

        return file_get_contents($path);
    }
}
