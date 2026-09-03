<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PublishingRequestId
{
    public function handle(Request $request, Closure $next)
    {
        $requestId = $this->resolveRequestId($request);
        $request->headers->set('X-Request-ID', $requestId);
        $request->attributes->set('publishing_request_id', $requestId);

        Log::info('Publishing API request started.', [
            'request_id' => $requestId,
            'method' => $request->method(),
            'path' => '/' . ltrim($request->path(), '/'),
        ]);

        $response = $next($request);
        $response->headers->set('X-Request-ID', $requestId);

        return $response;
    }

    private function resolveRequestId(Request $request): string
    {
        $incoming = trim((string) $request->headers->get('X-Request-ID'));

        if ($incoming !== '' && preg_match('/^[A-Za-z0-9][A-Za-z0-9._:-]{0,127}$/', $incoming)) {
            return $incoming;
        }

        return (string) Str::uuid();
    }
}
