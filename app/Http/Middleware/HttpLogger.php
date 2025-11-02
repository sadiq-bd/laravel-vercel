<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\HttpLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ErrorNotification;
use Carbon\Carbon;

class HttpLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Start timing
        $start = defined('LARAVEL_START') ? LARAVEL_START : microtime(true);

        // Generate or retrieve token
        $token = $request->cookie('id_token');
        if (empty($token) || strlen($token) > 100) {
            $token = substr(md5(uniqid()), 0, 100);
        }

        try {
            // Process the request and get the response
            $response = $next($request);
        } catch (\Exception $e) {
            // Log error and optionally send a notification
            Log::error('Request failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_uri' => $request->fullUrl(),
                'ip_address' => function_exists('getClientIp') ? getClientIp() : $request->ip(),
            ]);

            // Re-throw the exception to keep error handling as expected
            throw $e;
        }

		// requestBody
		$requestBodyLimit = 500;
		$requestBody = strlen($request->getContent()) > $requestBodyLimit 
					? substr($request->getContent(), 0, $requestBodyLimit) . '...<trimmed>'
					: $request->getContent();

        // Calculate response time
        $duration = round((microtime(true) - $start) * 1000, 2); // in milliseconds

        // Log request and response
        HttpLog::create([
            'id_token' => $token,
            'ip_address' => function_exists('getClientIp') ? getClientIp() : $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_method' => $request->method(),
            'request_uri' => $request->fullUrl(),
            'request_body' => $requestBody,
            'request_headers' => json_encode($request->headers->all()),
            'response_headers' => json_encode($response->headers->all()),
            'status_code' => $response->getStatusCode(),
            'response_size' => strlen($response->getContent()),
            'response_time' => $duration,
        ]);

        // Return the response with the token cookie
        return $response->withCookie(cookie()->forever('id_token', $token));
    }
}
