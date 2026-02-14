<?php

namespace App\ApiClients;

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterApiClient
{
    public function __construct(protected TwitterOAuth $twitter) {}

    public function tweet(string $text): array
    {
        return (array) $this->twitter->post('tweets', compact('text'));
    }
}
