<?php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Models\UserOAuthProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class ShowOAuthProviders extends Component
{
    public array $supportedProviders = ['google', 'github', 'discord'];
    public array $linkedProviders = [];
    public bool $showUnlinkConfirm = false;
    public string $providerToUnlink = '';
    
    protected $listeners = ['refreshOAuthProviders' => 'refreshOAuthProviders'];

    public function refreshOAuthProviders()
    {
        error_log('refreshOAuthProviders event received');
        $this->loadLinkedProviders();
    }

    public function mount()
    {
        $this->loadLinkedProviders();
        
        // Check if we need to refresh OAuth providers after linking
        if (session('refresh_oauth')) {
            session()->forget('refresh_oauth');
            $this->loadLinkedProviders();
        }
    }

    public function hydrate()
    {
        $this->loadLinkedProviders();
        
        // Check if we need to refresh OAuth providers after linking
        if (session('refresh_oauth')) {
            session()->forget('refresh_oauth');
            $this->loadLinkedProviders();
        }
    }

    public function loadLinkedProviders()
    {
        $user = Auth::user();
        $this->linkedProviders = UserOAuthProvider::getProvidersForUser($user->id)->toArray();
    }

    public function linkProvider(string $provider)
    {
        if (!in_array($provider, $this->supportedProviders)) {
            session()->flash('error', 'Unsupported OAuth provider.');
            return;
        }

        if (collect($this->linkedProviders)->contains('provider', $provider)) {
            session()->flash('error', 'This provider is already linked to your account.');
            return;
        }

        // Check if OAuth system is enabled
        if (!config('oauth.enabled')) {
            session()->flash('error', 'OAuth authentication is currently disabled.');
            return;
        }

        // Check if specific provider is enabled
        if (!config("oauth.providers.{$provider}.enabled")) {
            session()->flash('error', ucfirst($provider) . ' OAuth is currently disabled.');
            return;
        }

        // Redirect to OAuth provider with linking flag
        return redirect()->route('oauth.link', ['provider' => $provider]);
    }

    public function confirmUnlink(string $provider)
    {
        $this->providerToUnlink = $provider;
        $this->showUnlinkConfirm = true;
    }

    public function cancelUnlink()
    {
        $this->showUnlinkConfirm = false;
        $this->providerToUnlink = '';
    }

    public function unlinkProvider()
    {
        $user = Auth::user();
        
        // Check if the provider exists in linkedProviders array
        $providerExists = collect($this->linkedProviders)->contains('provider', $this->providerToUnlink);
        
        if ($providerExists) {
            // Check if user has a password or other OAuth providers before unlinking
            if ((empty($user->password) || $user->password === '') && count($this->linkedProviders) <= 1) {
                session()->flash('error', 'Cannot unlink OAuth provider. Please set a password first to maintain account access.');
                $this->cancelUnlink();
                return;
            }

            // Find and delete the OAuth provider record
            $oauthProvider = UserOAuthProvider::where('user_id', $user->id)
                ->where('provider', $this->providerToUnlink)
                ->first();
                
            if ($oauthProvider) {
                $oauthProvider->delete();
                session()->flash('message', ucfirst($this->providerToUnlink) . ' has been unlinked from your account.');
                $this->loadLinkedProviders();
            }
        }

        $this->cancelUnlink();
    }

    public function getProviderDisplayName(string $provider): string
    {
        return match($provider) {
            'google' => 'Google',
            'github' => 'GitHub', 
            'discord' => 'Discord',
            default => ucfirst($provider)
        };
    }

    public function getProviderIcon(string $provider): string
    {
        return match($provider) {
            'google' => 'fab fa-google',
            'github' => 'fab fa-github',
            'discord' => 'fab fa-discord',
            default => 'fas fa-link'
        };
    }

    public function render(): View
    {
        return view('livewire.profile.show-oauth-providers');
    }
}