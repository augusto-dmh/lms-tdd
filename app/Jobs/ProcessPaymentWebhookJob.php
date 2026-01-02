<?php

namespace App\Jobs;

use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;

class ProcessPaymentWebhookJob extends ProcessWebhookJob
{
    public function handle(): void
    {
        //
    }
}
