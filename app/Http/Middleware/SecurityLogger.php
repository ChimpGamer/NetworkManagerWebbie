<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecurityLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log authentication attempts
        if ($request->is('login') && $request->isMethod('POST')) {
            $this->logAuthenticationAttempt($request, $response);
        }

        // Log logout events
        if ($request->is('logout') && $request->isMethod('POST')) {
            $this->logLogout($request);
        }

        // Log failed authorization attempts
        if ($response->getStatusCode() === 403) {
            $this->logAuthorizationFailure($request);
        }

        // Log suspicious activities
        $this->logSuspiciousActivity($request, $response);

        return $response;
    }

    /**
     * Log authentication attempts
     */
    private function logAuthenticationAttempt(Request $request, Response $response)
    {
        $username = $request->input('username');
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $success = $response->getStatusCode() === 302 && !$response->headers->has('Location') || 
                   (str_contains($response->headers->get('Location', ''), '/') && !str_contains($response->headers->get('Location', ''), 'login'));

        $logData = [
            'event' => 'authentication_attempt',
            'username' => $username,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'success' => $success,
            'timestamp' => now()->toISOString(),
        ];

        if ($success) {
            Log::channel('security')->info('Successful login attempt', $logData);
        } else {
            Log::channel('security')->warning('Failed login attempt', $logData);
        }
    }

    /**
     * Log logout events
     */
    private function logLogout(Request $request)
    {
        $user = Auth::user();
        
        Log::channel('security')->info('User logout', [
            'event' => 'logout',
            'user_id' => $user?->id,
            'username' => $user?->username,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Log authorization failures
     */
    private function logAuthorizationFailure(Request $request)
    {
        $user = Auth::user();
        
        Log::channel('security')->warning('Authorization failure', [
            'event' => 'authorization_failure',
            'user_id' => $user?->id,
            'username' => $user?->username,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'route' => $request->route()?->getName(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Log suspicious activities
     */
    private function logSuspiciousActivity(Request $request, Response $response)
    {
        $suspiciousPatterns = [
            // SQL injection attempts
            '/union.*select/i',
            '/select.*from/i',
            '/drop.*table/i',
            '/insert.*into/i',
            '/update.*set/i',
            '/delete.*from/i',
            
            // XSS attempts
            '/<script/i',
            '/javascript:/i',
            '/on\w+\s*=/i',
            
            // Path traversal
            '/\.\.\//',
            '/\.\.\\\\//',
            
            // Command injection
            '/;\s*(cat|ls|pwd|whoami|id|uname)/i',
            '/\|\s*(cat|ls|pwd|whoami|id|uname)/i',
        ];

        $requestData = $request->all();
        $requestString = json_encode($requestData) . ' ' . $request->fullUrl();

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $requestString)) {
                Log::channel('security')->alert('Suspicious activity detected', [
                    'event' => 'suspicious_activity',
                    'pattern_matched' => $pattern,
                    'user_id' => Auth::id(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'route' => $request->route()?->getName(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'request_data' => $requestData,
                    'timestamp' => now()->toISOString(),
                ]);
                break; // Only log once per request
            }
        }

        // Log multiple failed requests from same IP
        if ($response->getStatusCode() >= 400) {
            $this->checkForBruteForce($request);
        }
    }

    /**
     * Check for potential brute force attacks
     */
    private function checkForBruteForce(Request $request)
    {
        $ip = $request->ip();
        $cacheKey = "failed_requests_{$ip}";
        
        $failedCount = cache()->get($cacheKey, 0) + 1;
        cache()->put($cacheKey, $failedCount, now()->addMinutes(15));

        if ($failedCount >= 10) {
            Log::channel('security')->alert('Potential brute force attack', [
                'event' => 'brute_force_attempt',
                'ip_address' => $ip,
                'failed_count' => $failedCount,
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toISOString(),
            ]);
        }
    }
}