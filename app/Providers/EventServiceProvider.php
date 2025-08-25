<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SocialiteWasCalled::class => [
            'SocialiteProviders\\Discord\\DiscordExtendSocialite@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Configure Discord HTTP client after SocialiteWasCalled event
        Event::listen(SocialiteWasCalled::class, function () {
            $this->configureDiscordHttpClient();
        });
    }

    /**
     * Configure Discord HTTP client with SSL settings
     */
    private function configureDiscordHttpClient(): void
    {
        try {
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
            
            // Configure Discord provider with the custom HTTP client
            if (config('services.discord.client_id')) {
                Socialite::driver('discord')->setHttpClient($httpClient);
            }
        } catch (\Exception $e) {
            // Silently continue if Discord configuration fails
        }
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
