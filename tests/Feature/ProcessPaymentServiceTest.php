<?php

use App\Mail\PaymentSuccess;
use App\Models\Course;
use App\Models\PurchasedCourse;
use App\Models\User;
use App\Services\ProcessPaymentService;
use Illuminate\Support\Facades\Mail;
use Spatie\WebhookClient\Models\WebhookCall;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;

it('stores purchase for a buyer that is already a user', function () {
    // Arrange
    config()->set('services.paddle.base_url', 'https://api.paddle.com');
    $paymentProviderBaseUrl = rtrim(config('services.paddle.base_url'), '/');
    $customerId = 'ctm_01hv6y1jedq4p1n0yqn5ba3ky4';
    $getCustomerUrl = "{$paymentProviderBaseUrl}/customers/ctm_01hv6y1jedq4p1n0yqn5ba3ky4";

    Queue::fake();
    Http::fake([
        $getCustomerUrl  => Http::response([
            'data' => [
                'name' => 'Test',
                'email' => 'test@test.at',
                // ...
            ],
        ])
    ]);

    User::factory()->create(['email' => 'test@test.at']);
    Course::factory()->create(['paddle_price_id' => 'pri_01gsz8x8sawmvhz1pv30nge1ke']);

    $webhookIncomingData = WebhookCall::create([
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

    // Assert
    assertDatabaseEmpty(PurchasedCourse::class);
    assertDatabaseCount(User::class, 1);
    assertDatabaseHas(User::class, [
        'email' => 'test@test.at',
    ]);

    // Act
    app(ProcessPaymentService::class)->handle($webhookIncomingData->payload['data']);

    // Assert
    assertDatabaseCount(PurchasedCourse::class, 1);
    assertDatabaseCount(User::class, 1);
    assertDatabaseHas(User::class, [
        'email' => 'test@test.at',
    ]);
});

it('stores purchase for a buyer that is not yet a user and makes them one', function () {
    // Assert
    assertDatabaseEmpty(PurchasedCourse::class);
    assertDatabaseEmpty(User::class);

    // Arrange
    config()->set('services.paddle.base_url', 'https://api.paddle.com');
    $paymentProviderBaseUrl = rtrim(config('services.paddle.base_url'), '/');
    $customerId = 'ctm_01hv6y1jedq4p1n0yqn5ba3ky4';
    $getCustomerUrl = "{$paymentProviderBaseUrl}/customers/ctm_01hv6y1jedq4p1n0yqn5ba3ky4";

    Queue::fake();
    Http::fake([
        $getCustomerUrl  => Http::response([
            'data' => [
                'name' => 'Test',
                'email' => 'test@test.at',
                // ...
            ],
        ])
    ]);

    Course::factory()->create(['paddle_price_id' => 'pri_01gsz8x8sawmvhz1pv30nge1ke']);

    $webhookIncomingData = WebhookCall::create([
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

    // Act
    app(ProcessPaymentService::class)->handle($webhookIncomingData->payload['data']);

    // Assert
    assertDatabaseCount(PurchasedCourse::class, 1);
    assertDatabaseCount(User::class, 1);
    assertDatabaseHas(User::class, [
        'name' => 'Test',
        'email' => 'test@test.at',
    ]);
});

it('sends out purchase email', function () {
    // Arrange
    Mail::fake();
    $user = User::factory()->create(['email' => 'test@test.at']);

    config()->set('services.paddle.base_url', 'https://api.paddle.com');
    $paymentProviderBaseUrl = rtrim(config('services.paddle.base_url'), '/');
    $customerId = 'ctm_01hv6y1jedq4p1n0yqn5ba3ky4';
    $getCustomerUrl = "{$paymentProviderBaseUrl}/customers/ctm_01hv6y1jedq4p1n0yqn5ba3ky4";

    Http::fake([
        $getCustomerUrl  => Http::response([
            'data' => [
                'name' => 'Test',
                'email' => 'test@test.at',
                // ...
            ],
        ])
    ]);

    Course::factory()->create(['paddle_price_id' => 'pri_01gsz8x8sawmvhz1pv30nge1ke']);

    $webhookIncomingData = WebhookCall::create([
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

    // Act
    app(ProcessPaymentService::class)->handle($webhookIncomingData->payload['data']);

    // Assert
    Mail::assertSent(PaymentSuccess::class, function (PaymentSuccess $mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});
