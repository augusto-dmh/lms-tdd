<?php

namespace App\Services;

use App\Models\Course;
use App\Models\PurchasedCourse;
use App\Models\User;
use App\ApiClients\PaddleBillingApiClient;
use App\Mail\PaymentSuccessMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessPaymentService
{
    public function handle(array $payload): void
    {
        Log::info([
            'payload' => $payload,
        ]);
        $userEmail = $payload['custom_data']['user']['email'];
        $priceId = $payload['items']['0']['price_id'];

        $course = Course::query()
            ->where('paddle_price_id', $priceId)
            ->first();
        $user = User::query()
            ->firstOrCreate(
                ['email' => $userEmail],
                [
                    'name' => $userEmail,
                    'email' => $userEmail,
                    'password' => uuid_create(),
                ],
            );

        $user->purchasedCourses()->attach($course);
        Mail::to($user)->send(new PaymentSuccessMail($course));
    }
}
