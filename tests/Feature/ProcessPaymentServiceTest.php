<?php

use App\Mail\PaymentSuccessMail;
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
    Queue::fake();

    $user = User::factory()->create(['email' => 'test@test.at']);
    Course::factory()->create(['paddle_price_id' => 'pri_01gsz8x8sawmvhz1pv30nge1ke']);

    $webhookIncomingData = getMockedPaymentWebhookCall($user);

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
    Queue::fake();

    Course::factory()->create(['paddle_price_id' => 'pri_01gsz8x8sawmvhz1pv30nge1ke']);

    $webhookIncomingData = getMockedPaymentWebhookCall();

    // Act
    app(ProcessPaymentService::class)->handle($webhookIncomingData->payload['data']);

    // Assert
    assertDatabaseCount(PurchasedCourse::class, 1);
    assertDatabaseCount(User::class, 1);
    assertDatabaseHas(User::class, [
        'name' => 'test@test.at',
        'email' => 'test@test.at',
    ]);
});

it('sends out purchase email', function () {
    // Arrange
    Mail::fake();

    $user = User::factory()->create(['email' => 'test@test.at']);
    Course::factory()->create(['paddle_price_id' => 'pri_01gsz8x8sawmvhz1pv30nge1ke']);

    $webhookIncomingData = getMockedPaymentWebhookCall($user);

    // Act
    app(ProcessPaymentService::class)->handle($webhookIncomingData->payload['data']);

    // Assert
    Mail::assertSent(PaymentSuccessMail::class, function (PaymentSuccessMail $mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

function getMockedPaymentWebhookCall(?User $user = null): WebhookCall
{
    return WebhookCall::create([
        'name' => 'transaction.completed',
        'url' => '',
        'payload' => [
            'event_id' => 'ntfsimevt_01keqr6v334mjag77damk4hezs',
            'event_type' => 'transaction.completed',
            'occurred_at' => '2026-01-12T00:03:04.420024Z',
            'notification_id' => 'ntfsimntf_01keqr6v642k2qpz9671y48x7h',
            'data' => [
                'id' => 'txn_01hv8wptq8987qeep44cyrewp9',
                'items' => [
                    [
                        'price' => [
                            'id' => 'pri_01gsz8x8sawmvhz1pv30nge1ke',
                            'name' => null,
                            'type' => 'standard',
                            'status' => 'active',
                            'quantity' => [
                                'maximum' => 999999,
                                'minimum' => 1,
                            ],
                            'tax_mode' => 'location',
                            'created_at' => '2025-12-31T17:27:29.650639Z',
                            'product_id' => 'pro_01gsz4t5hdjse780zja8vvr7jg',
                            'unit_price' => [
                                'amount' => '9900',
                                'currency_code' => 'USD',
                            ],
                            'updated_at' => '2026-01-11T23:33:15.162079Z',
                            'custom_data' => [
                                'user_id' => '1',
                            ],
                            'description' => 'Testing',
                            'trial_period' => null,
                            'billing_cycle' => null,
                            'unit_price_overrides' => [],
                        ],
                        'price_id' => 'pri_01gsz8x8sawmvhz1pv30nge1ke',
                        'quantity' => 1,
                        'proration' => null,
                    ],
                ],
                'origin' => 'web',
                'status' => 'completed',
                'details' => [
                    'totals' => [
                        'fee' => '0',
                        'tax' => '0',
                        'total' => '0',
                        'credit' => '0',
                        'balance' => '0',
                        'discount' => '0',
                        'earnings' => '0',
                        'subtotal' => '9900',
                        'grand_total' => '0',
                        'currency_code' => 'USD',
                        'credit_to_balance' => '0',
                    ],
                    'line_items' => [
                        [
                            'id' => 'txnitm_01kfctap28qkpqgwfvhdg2whrg',
                            'totals' => [
                                'tax' => '0',
                                'total' => '0',
                                'discount' => '0',
                                'subtotal' => '9900',
                            ],
                            'product' => [
                                'id' => 'pro_01gsz4t5hdjse780zja8vvr7jg',
                                'name' => 'AeroEdit Pro',
                                'type' => 'standard',
                                'status' => 'active',
                                'image_url' => null,
                                'created_at' => '2025-12-31T15:18:31.399Z',
                                'updated_at' => '2026-01-11T23:38:41.911Z',
                                'custom_data' => [
                                    'user_id' => '1',
                                ],
                                'description' => null,
                                'tax_category' => 'standard',
                            ],
                            'price_id' => 'pri_01gsz8x8sawmvhz1pv30nge1ke',
                            'quantity' => 1,
                            'tax_rate' => '0',
                            'unit_totals' => [
                                'tax' => '0',
                                'total' => '0',
                                'discount' => '0',
                                'subtotal' => '9900',
                            ],
                        ],
                    ],
                    'payout_totals' => [
                        'fee' => '0',
                        'tax' => '0',
                        'total' => '0',
                        'credit' => '0',
                        'balance' => '0',
                        'discount' => '0',
                        'earnings' => '0',
                        'fee_rate' => '0',
                        'subtotal' => '9900',
                        'grand_total' => '0',
                        'currency_code' => 'USD',
                        'credit_to_balance' => '0',
                        'exchange_rate' => '1',
                    ],
                    'tax_rates_used' => [
                        [
                            'totals' => [
                                'tax' => '0',
                                'total' => '0',
                                'discount' => '0',
                                'subtotal' => '9900',
                            ],
                            'tax_rate' => '0',
                        ],
                    ],
                    'adjusted_totals' => [
                        'fee' => '0',
                        'tax' => '0',
                        'total' => '0',
                        'earnings' => '0',
                        'subtotal' => '0',
                        'grand_total' => '0',
                        'retained_fee' => '0',
                        'currency_code' => 'USD',
                    ],
                ],
                'checkout' => [
                    'url' => 'https://aeroedit.com/pay?_ptxn=txn_01hv8wptq8987qeep44cyrewp9',
                ],
                'payments' => [
                    [
                        'amount' => '0',
                        'status' => 'captured',
                        'created_at' => '2026-01-20T04:24:12.805291Z',
                        'error_code' => null,
                        'captured_at' => '2026-01-20T04:24:12.833412Z',
                        'method_details' => [
                            'card' => [
                                'type' => 'visa',
                                'last4' => '3184',
                                'expiry_year' => 2025,
                                'expiry_month' => 1,
                                'cardholder_name' => 'Michael McGovern',
                            ],
                            'type' => 'card',
                            'south_korea_local_card' => null,
                        ],
                        'payment_method_id' => null,
                        'payment_attempt_id' => 'd5b2364f-3f9f-49c4-aedf-8f700c557c18',
                        'stored_payment_method_id' => '00000000-0000-0000-0000-000000000000',
                    ],
                ],
                'billed_at' => '2026-01-20T04:24:13.269655Z',
                'address_id' => 'add_01kfctanszsznh6zrnf2w80vg7',
                'created_at' => '2026-01-20T04:24:03.097665Z',
                'invoice_id' => 'inv_01kfctas1d4jkapz3cawq4wnby',
                'revised_at' => null,
                'updated_at' => '2026-01-20T04:24:15.09282783Z',
                'business_id' => null,
                'custom_data' => [
                    'user' => [
                        'email' => $user->email ?? 'test@test.at',
                    ],
                ],
                'customer_id' => 'ctm_01kfcpag96q14kva2zvx7pj733',
                'discount_id' => null,
                'receipt_data' => null,
                'currency_code' => 'USD',
                'billing_period' => null,
                'invoice_number' => '77064-10011',
                'billing_details' => null,
                'collection_mode' => 'automatic',
                'subscription_id' => null,
            ],
        ],
    ]);
}
