<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LogPaddleWebhook
{
    public function handle(Request $request, Closure $next)
    {
        // IMPORTANT: get the RAW body before anything else touches it
        $raw = $request->getContent();

        // Store raw request artifacts for later inspection
        Storage::put('webhooks/paddle_last_body.json', $raw);
        Storage::put('webhooks/paddle_last_headers.json', json_encode($request->headers->all(), JSON_PRETTY_PRINT));
        Storage::put('webhooks/paddle_last_meta.json', json_encode([
            'method' => $request->method(),
            'full_url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'content_type' => $request->header('Content-Type'),
            'received_at' => now()->toIso8601String(),
            'raw_len' => strlen($raw),
            'raw_sha256' => hash('sha256', $raw),
            'paddle_signature' => $request->header('Paddle-Signature'),
        ], JSON_PRETTY_PRINT));

        // Log minimal metadata (safe)
        Log::info('Paddle webhook inbound', [
            'method' => $request->method(),
            'path' => $request->path(),
            'content_type' => $request->header('Content-Type'),
            'raw_len' => strlen($raw),
            'raw_sha256' => hash('sha256', $raw),
            'paddle_signature' => $request->header('Paddle-Signature'),
        ]);

        // Log a raw preview (avoid huge logs)
        Log::info('Paddle webhook body (raw preview)', [
            'raw_preview' => mb_substr($raw, 0, 2000),
        ]);

        // Log parsed JSON (trimmed) if JSON
        $json = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            Log::info('Paddle webhook body (json preview)', [
                'json_preview' => $this->trimDeep($json, 6, 50),
            ]);
        } else {
            Log::warning('Paddle webhook body is not valid JSON', [
                'json_error' => json_last_error_msg(),
            ]);
        }

        return $next($request);
    }

    /**
     * Trim deep arrays/strings so logs stay readable.
     */
    private function trimDeep(mixed $value, int $maxDepth, int $maxStringLen, int $depth = 0): mixed
    {
        if ($depth >= $maxDepth) {
            return '...trimmed...';
        }

        if (is_array($value)) {
            $out = [];
            foreach ($value as $k => $v) {
                $out[$k] = $this->trimDeep($v, $maxDepth, $maxStringLen, $depth + 1);
            }
            return $out;
        }

        if (is_string($value) && mb_strlen($value) > $maxStringLen) {
            return mb_substr($value, 0, $maxStringLen) . '...';
        }

        return $value;
    }
}
