<?php

use Spatie\WebhookClient\Models\WebhookCall;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

it('stores a paddle purchase request', function () {
    // Assert
    assertDatabaseEmpty(WebhookCall::class);

    // Arrange
    config()->set('webhook-client.configs.1.signing_secret', 'pdl_ntfset_012149jdg56fgqg37yjm733r4n_+64BsMfoeYkW5HI/V0jXo1nisZkALR8V');

    $payload = [
        'event_id' => 'evt_01hxxxxxxx',
        'event_type' => 'transaction.completed',
        'occurred_at' => '2026-01-01T12:00:00.000000Z',
        'notification_id' => 'ntf_01hxxxxxxx',
        'data' => [
            'id' => 'txn_01hv8wptq8987qeep44cyrewp9',
            'status' => 'completed',
            'customer_id' => 'ctm_01hv6y1jedq4p1n0yqn5ba3ky4',
            'subscription_id' => 'sub_01hv8x29kz0t586xy6zn1a62ny',
            'currency_code' => 'USD',
            'items' => [
                [
                    'price' => [
                        'id' => 'pri_01gsz8x8sawmvhz1pv30nge1ke',
                        'product_id' => 'pro_01gsz4t5hdjse780zja8vvr7jg'
                    ],
                    'quantity' => 1
                ]
            ],
            'details' => [
                'totals' => [
                    'subtotal' => '3000',
                    'tax' => '266',
                    'total' => '3266',
                    'currency_code' => 'USD'
                ]
            ],
            'created_at' => '2024-04-12T10:12:33.2014Z',
            'billed_at' => '2024-04-12T10:18:48.294633Z'
        ]
    ];

    // Generate signature
    $timestamp = time();
    $jsonPayload = json_encode($payload);
    $signedPayload = $timestamp . ':' . $jsonPayload;
    $secretKey = config('webhook-client.configs.1.signing_secret');
    $signature = hash_hmac('sha256', $signedPayload, $secretKey);

    // Act
    postJson('webhooks/payments', $payload, [
        'Paddle-Signature' => "ts={$timestamp};h1={$signature}"
    ]);

    // Assert
    assertDatabaseCount(WebhookCall::class, 1);
});

it('does not store invalid paddle purchase request', function () {
    // Assert
    assertDatabaseEmpty(WebhookCall::class);

    // Arrange & Assert
    post('webhooks/payments', []);

    // Assert
    assertDatabaseEmpty(WebhookCall::class);
});
