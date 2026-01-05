<?php

namespace App\Services;

use App\Models\Course;
use App\Models\PurchasedCourse;
use App\Models\User;
use App\ApiClients\PaddleBillingApiClient;

class ProcessPaymentService
{
    public function __construct(
        private readonly PaddleBillingApiClient $paddle
    ) {}

    public function handle(array $payload): void
    {
        $customerId = $payload['customer_id'];
        $priceId = $payload['items']['0']['price']['id'];

        $customer = $this->paddle->getCustomer($customerId);

        $course = Course::query()
            ->where('paddle_price_id', $priceId)
            ->first();
        $user = User::query()
            ->where('email', $customer['email'])
            ->firstOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'email' => $customer['email'],
                    'password' => uuid_create(),
                ],
            );

        $user->purchasedCourses()->attach($course);
    }
}
