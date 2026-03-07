<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Log request info
        Log::info('Request received', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => auth()->id(),
            'is_authenticated' => auth()->check(),
        ]);

        $response = $next($request);

        // Log response info
        Log::info('Response sent', [
            'status' => $response->getStatusCode(),
        ]);

        return $response;
    }
}

