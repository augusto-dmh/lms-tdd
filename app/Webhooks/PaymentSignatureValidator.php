<?php

namespace App\Webhooks;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class PaymentSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $signature = $request->header('Paddle-Signature');

        if (!$signature) {
            return false;
        }

        // Extract timestamp and signature from header
        $parts = $this->parseSignature($signature);

        if (!isset($parts['ts']) || !isset($parts['h1'])) {
            return false;
        }

        $timestamp = $parts['ts'];
        $expectedSignature = $parts['h1'];

        // Get the raw request body
        $payload = $request->getContent();

        // Build signed payload: timestamp:body
        $signedPayload = $timestamp . ':' . $payload;

        // Get secret key from config
        $secretKey = $config->signingSecret;

        // Compute HMAC signature
        $computedSignature = hash_hmac('sha256', $signedPayload, $secretKey);

        // Compare signatures using timing-safe comparison
        if (!hash_equals($computedSignature, $expectedSignature)) {
            return false;
        }

        // Optional: Check timestamp to prevent replay attacks
        $tolerance = 5; // seconds
        $currentTime = time();

        if (abs($currentTime - $timestamp) > $tolerance) {
            return false;
        }

        return true;
    }

    private function parseSignature(string $signature): array
    {
        $parts = [];
        $elements = explode(';', $signature);

        foreach ($elements as $element) {
            $keyValue = explode('=', $element, 2);
            if (count($keyValue) === 2) {
                $parts[$keyValue[0]] = $keyValue[1];
            }
        }

        return $parts;
    }
}
