<?php

namespace App\ApiClients;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

/** @codeCoverageIgnore */
final class PaddleBillingApiClient
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl,
        private readonly string $apiVersion = '1',
    ) {}

    /**
     * Fetch a Paddle customer by its Paddle ID (e.g. "ctm_...").
     *
     * @throws RequestException When Paddle returns a 4xx/5xx response.
     */
    public function getCustomer(string $customerId): array
    {
        $customerId = rawurlencode($customerId);

        $response = Http::baseUrl(rtrim($this->baseUrl, '/'))
            ->acceptJson()
            ->withToken($this->apiKey)
            ->withHeaders(['Paddle-Version' => $this->apiVersion])
            ->get("/customers/{$customerId}")
            ->throw();

        return $response->json('data') ?? [];
    }
}
