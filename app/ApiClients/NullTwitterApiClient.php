<?php

namespace App\ApiClients;

use App\Interfaces\TwitterApiClientInterface;

class NullTwitterApiClient implements TwitterApiClientInterface
{
    public function __construct() {}

    public function tweet(string $text): array
    {
        return [];
    }
}
