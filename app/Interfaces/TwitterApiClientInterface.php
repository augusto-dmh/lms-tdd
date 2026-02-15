<?php

namespace App\Interfaces;

interface TwitterApiClientInterface
{
    /**
     * @return array{data: array{id: string, text: string}, errors?: array<int, array{title: string, type: string, detail: string, status: int}>}|array{code: int, message: string}
     */
    public function tweet(string $text): array;
}
