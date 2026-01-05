<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\PurchasedCourse;
use App\Models\User;
use App\Services\ProcessPaymentService;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;

class ProcessPaymentWebhookJob extends ProcessWebhookJob
{
    public function handle(ProcessPaymentService $service): void
    {
        $service->handle($this->webhookCall->payload['data']);
    }
}
