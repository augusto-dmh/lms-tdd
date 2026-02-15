<?php

namespace App\Interfaces;

interface TwitterApiClientInterface
{
    public function tweet(string $text): array;
}
