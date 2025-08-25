<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;

class SocialiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configure HTTP client for Socialite with SSL verification settings
        $this->configureSocialiteHttpClient();
    }

    /**
     * Configure Socialite HTTP client with custom options
     */
    private function configureSocialiteHttpClient(): void
    {
        // Get SSL verification setting from environment
        $verifySsl = env('CURL_VERIFY_SSL', true);
        
        // Configure Guzzle client options
        $clientOptions = [
            'verify' => $verifySsl,
            'timeout' => 30,
        ];
        
        // If SSL verification is disabled, also disable peer verification
        if (!$verifySsl) {
            $clientOptions['curl'] = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ];
        }
        
        // Create custom HTTP client
        $httpClient = new Client($clientOptions);
        
        // Configure supported providers with the custom HTTP client
        // Only configure providers that are natively supported by Laravel Socialite
        foreach (['google', 'github'] as $provider) {
            if (config("services.{$provider}.client_id")) {
                try {
                    Socialite::driver($provider)->setHttpClient($httpClient);
                } catch (\Exception $e) {
                    // Silently continue if provider configuration fails
                }
            }
        }
        
        // Discord is handled by SocialiteProviders, so we'll configure it differently
        // The HTTP client configuration for Discord will be handled in the EventServiceProvider
    }
}