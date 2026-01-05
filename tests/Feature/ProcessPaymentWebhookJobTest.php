<?php

use App\Jobs\ProcessPaymentWebhookJob;
use App\Models\Course;
use App\Services\ProcessPaymentService;
use App\Models\PurchasedCourse;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Spatie\WebhookClient\Models\WebhookCall;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;

it('sends the webhook payload to the payment processing service', function () {
    // Assert
    assertDatabaseEmpty(WebhookCall::class);
    assertDatabaseEmpty(PurchasedCourse::class);

    // Arrange
    config()->set('services.paddle.base_url', 'https://api.paddle.com');
    $paymentProviderBaseUrl = rtrim(config('services.paddle.base_url'), '/');
    $customerId = 'ctm_01hv6y1jedq4p1n0yqn5ba3ky4';
    $getCustomerUrl = "{$paymentProviderBaseUrl}/customers/ctm_01hv6y1jedq4p1n0yqn5ba3ky4";

    Queue::fake();
    Http::fake([
        $getCustomerUrl  => Http::response([
            'data' => [
                'email' => 'test@test.at',
                // ...
            ],
        ])
    ]);

    User::factory()->create(['email' => 'test@test.at']);
    Course::factory()->create(['paddle_price_id' => 'pri_01gsz8x8sawmvhz1pv30nge1ke']);

    $paymentWebhookCall = WebhookCall::create([
        'name' => 'transaction.completed',
        'url' => '',
        'payload' => [
            'event_id' => 'evt_01hxxxxxxx',
            'event_type' => 'transaction.completed',
            'occurred_at' => '2026-01-01T12:00:00.000000Z',
            'notification_id' => 'ntf_01hxxxxxxx',
            'data' => [
                'id' => 'txn_01hv8wptq8987qeep44cyrewp9',
                'status' => 'completed',
                'customer_id' => $customerId,
                'subscription_id' => 'sub_01hv8x29kz0t586xy6zn1a62ny',
                'currency_code' => 'USD',
                'items' => [
                    [
                        'price' => [
                            'id' => 'pri_01gsz8x8sawmvhz1pv30nge1ke',
                            'product_id' => 'pro_01gsz4t5hdjse780zja8vvr7jg',
                        ],
                        'quantity' => 1,
                    ],
                ],
                'details' => [
                    'totals' => [
                        'subtotal' => '3000',
                        'tax' => '266',
                        'total' => '3266',
                        'currency_code' => 'USD',
                    ],
                ],
                'created_at' => '2024-04-12T10:12:33.2014Z',
                'billed_at' => '2024-04-12T10:18:48.294633Z',
            ],
        ],
    ]);

    $processPaymentServiceMock = mock(ProcessPaymentService::class);

    // Assert
    $processPaymentServiceMock
        ->shouldReceive('handle')
        ->once()
        ->with($paymentWebhookCall->payload['data']);

    // Act
    $job = new ProcessPaymentWebhookJob($paymentWebhookCall)
        ->handle($processPaymentServiceMock);
});
