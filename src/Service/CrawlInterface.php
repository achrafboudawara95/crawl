<?php

namespace App\Service;

interface CrawlInterface
{
    public function getData(string $page): array;
}