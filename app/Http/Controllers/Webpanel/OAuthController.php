<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOAuthProvider;
use App\Models\OAuthInvite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    /**
     * Supported OAuth providers
     */
    private array $supportedProviders = ['google', 'github', 'discord'];

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'redirectToProviderForLinking', 'handleProviderLinkingCallback']);
    }

    /**
     * Redirect to OAuth provider
     */
    public function redirectToProvider(Request $request, string $provider): RedirectResponse
    {
        if (!in_array($provider, $this->supportedProviders)) {
            return redirect()->route('auth.login')
                ->withErrors(['oauth' => 'Unsupported OAuth provider.']);
        }

        // Check if OAuth system is globally enabled
        if (!config('oauth.enabled')) {
            return redirect()->route('auth.login')
                ->withErrors(['oauth' => 'OAuth authentication is currently disabled.']);
        }

        // Check if specific provider is enabled
        if (!config("oauth.providers.{$provider}.enabled")) {
            return redirect()->route('auth.login')
                ->withErrors(['oauth' => ucfirst($provider) . ' OAuth is currently disabled.']);
        }

        try {
            // Store invite code in session if provided
            if ($request->has('invite_code')) {
                $request->session()->put('oauth_invite_code', $request->invite_code);
            }

            return Socialite::driver($provider)->redirect();
        } catch (Exception $e) {
            return redirect()->route('auth.login')
                ->withErrors(['oauth' => 'OAuth configuration error. Please contact administrator.']);
        }
    }

    /**
     * Handle OAuth callback
     */
    public function handleProviderCallback(string $provider, Request $request): RedirectResponse
    {
        if (!in_array($provider, $this->supportedProviders)) {
            return redirect()->route('auth.login')
                ->withErrors(['oauth' => 'Unsupported OAuth provider.']);
        }

        try {
            // Check if OAuth system is globally enabled
            if (!config('oauth.enabled')) {
                return redirect()->route('auth.login')
                    ->withErrors(['oauth' => 'OAuth authentication is currently disabled.']);
            }

            // Check if specific provider is enabled
            if (!config("oauth.providers.{$provider}.enabled")) {
                return redirect()->route('auth.login')
                    ->withErrors(['oauth' => ucfirst($provider) . ' OAuth is currently disabled.']);
            }

            // Check if OAuth registration is enabled
            if (!config('oauth.registration.enabled')) {
                return redirect()->route('auth.login')
                    ->withErrors(['oauth' => 'OAuth registration is currently disabled.']);
            }

            // Rate limiting
            $key = 'oauth-callback:' . $request->ip();
            if (config('oauth.security.rate_limit.enabled') && 
                RateLimiter::tooManyAttempts($key, config('oauth.security.rate_limit.max_attempts'))) {
                return redirect()->route('auth.login')
                    ->withErrors(['oauth' => 'Too many OAuth attempts. Please try again later.']);
            }

            $oauthUser = Socialite::driver($provider)->user();
            
            // Check if this OAuth account is already linked to any user
            $existingOAuthProvider = UserOAuthProvider::findByProvider($provider, $oauthUser->getId());
            
            if ($existingOAuthProvider) {
                // OAuth account is already linked to a user
                if (Auth::check() && Auth::id() !== $existingOAuthProvider->user_id) {
                    // Different user is logged in, show error
                    return redirect()->route('auth.login')
                        ->with('error', __('This :provider account is already linked to another user.', ['provider' => $this->getProviderDisplayName($provider)]));
                }
                
                // Log in the existing user
                Auth::login($existingOAuthProvider->user, true);
                RateLimiter::clear($key);
                $request->session()->regenerate();
                return redirect()->intended('/');
            }
            
            // Get invite code from session if available
            $inviteCode = $request->session()->get('oauth_invite_code');
            if ($inviteCode) {
                $request->merge(['invite_code' => $inviteCode]);
                $request->session()->forget('oauth_invite_code');
            }
            
            // New user registration - check restrictions
            $restrictionCheck = $this->checkRegistrationRestrictions($oauthUser, $request);
            if ($restrictionCheck !== true) {
                RateLimiter::hit($key, config('oauth.security.rate_limit.decay_minutes', 60) * 60);
                return redirect()->route('auth.login')
                    ->withErrors(['oauth' => $restrictionCheck]);
            }
            
            // Prepare user data
            $userData = [
                'id' => $oauthUser->getId(),
                'name' => $oauthUser->getName(),
                'nickname' => $oauthUser->getNickname(),
                'email' => $oauthUser->getEmail(),
                'avatar' => $oauthUser->getAvatar(),
            ];

            // Create new user with multi-provider support
            $user = User::createFromOAuth($provider, $userData);
            
            // Handle invite usage if applicable
            if ($request->has('invite_code')) {
                $invite = OAuthInvite::findValidInvite($request->invite_code, $oauthUser->getEmail());
                if ($invite) {
                    $invite->use($user->id);
                }
            }
            
            // Handle admin approval mode
            if (config('oauth.registration.mode') === 'admin_approval') {
                $user->update([
                    'is_active' => false,
                    'approval_status' => 'pending'
                ]);
                // TODO: Send notification to admins
                return redirect()->route('auth.login')
                    ->with('message', 'Your account has been created and is pending admin approval. You will be notified once approved.');
            }

            // Log the user in
            Auth::login($user, true);
            RateLimiter::clear($key);

            // Regenerate session
            $request->session()->regenerate();

            return redirect()->intended('/');

        } catch (Exception $e) {
            \Log::error('OAuth authentication failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            RateLimiter::hit($key, config('oauth.security.rate_limit.decay_minutes', 60) * 60);
            return redirect()->route('auth.login')
                ->withErrors(['oauth' => 'OAuth authentication failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Redirect to OAuth provider for linking to existing account
     */
    public function redirectToProviderForLinking(Request $request, string $provider): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->withErrors(['oauth' => 'You must be logged in to link OAuth providers.']);
        }

        if (!in_array($provider, $this->supportedProviders)) {
            return redirect()->route('profile.index')
                ->withErrors(['oauth' => 'Unsupported OAuth provider.']);
        }

        // Check if OAuth system is globally enabled
        if (!config('oauth.enabled')) {
            return redirect()->route('profile.index')
                ->withErrors(['oauth' => 'OAuth authentication is currently disabled.']);
        }

        // Check if specific provider is enabled
        if (!config("oauth.providers.{$provider}.enabled")) {
            return redirect()->route('profile.index')
                ->withErrors(['oauth' => ucfirst($provider) . ' OAuth is currently disabled.']);
        }

        try {
            // Store linking flag in session
            $request->session()->put('oauth_linking_mode', true);
            $request->session()->put('oauth_linking_user_id', Auth::id());

            // Set the callback URL for linking
            $linkingCallbackUrl = route('oauth.link.callback', ['provider' => $provider]);
            
            return Socialite::driver($provider)
                ->redirectUrl($linkingCallbackUrl)
                ->redirect();
        } catch (Exception $e) {
            return redirect()->route('profile.index')
                ->withErrors(['oauth' => 'OAuth configuration error. Please contact administrator.']);
        }
    }

    /**
     * Handle OAuth callback for linking to existing account
     */
    public function handleProviderLinkingCallback(string $provider, Request $request): RedirectResponse
    {
        if (!in_array($provider, $this->supportedProviders)) {
            return redirect()->route('profile.index')
                ->withErrors(['oauth' => 'Unsupported OAuth provider.']);
        }

        // Check if we're in linking mode
        if (!$request->session()->has('oauth_linking_mode') || !$request->session()->has('oauth_linking_user_id')) {
            return redirect()->route('profile.index')
                ->withErrors(['oauth' => 'Invalid linking session.']);
        }

        $userId = $request->session()->get('oauth_linking_user_id');
        $user = User::find($userId);

        if (!$user || !Auth::check() || Auth::id() !== $userId) {
            $request->session()->forget(['oauth_linking_mode', 'oauth_linking_user_id']);
            return redirect()->route('auth.login')
                ->withErrors(['oauth' => 'Invalid linking session. Please log in again.']);
        }

        try {
            // Check if OAuth system is globally enabled
            if (!config('oauth.enabled')) {
                return redirect()->route('profile.index')
                    ->withErrors(['oauth' => 'OAuth authentication is currently disabled.']);
            }

            // Check if specific provider is enabled
            if (!config("oauth.providers.{$provider}.enabled")) {
                return redirect()->route('profile.index')
                    ->withErrors(['oauth' => ucfirst($provider) . ' OAuth is currently disabled.']);
            }

            $oauthUser = Socialite::driver($provider)->user();
            
            // Check if this OAuth account is already linked to another user
            $existingOAuthProvider = UserOAuthProvider::findByProvider($provider, $oauthUser->getId());
            if ($existingOAuthProvider) {
                if ($existingOAuthProvider->user_id !== $user->id) {
                    $request->session()->forget(['oauth_linking_mode', 'oauth_linking_user_id']);
                    return redirect()->route('profile.index')
                        ->withErrors(['oauth' => 'This ' . ucfirst($provider) . ' account is already linked to another user.']);
                } else {
                    // OAuth account is already linked to current user
                    $request->session()->forget(['oauth_linking_mode', 'oauth_linking_user_id']);
                    return redirect()->route('profile.index')
                        ->with('message', ucfirst($provider) . ' is already linked to your account!');
                }
            }

            // Check if user already has this provider linked
            if ($user->hasOAuthProvider($provider)) {
                $request->session()->forget(['oauth_linking_mode', 'oauth_linking_user_id']);
                return redirect()->route('profile.index')
                    ->with('message', ucfirst($provider) . ' is already linked to your account!');
            }

            // Link the OAuth provider to the current user
            $oauthData = [
                'id' => $oauthUser->getId(),
                'name' => $oauthUser->getName(),
                'nickname' => $oauthUser->getNickname(),
                'email' => $oauthUser->getEmail(),
                'avatar' => $oauthUser->getAvatar(),
            ];
            
            $user->linkOAuthProvider($provider, $oauthData);
            
            // Update user avatar and email if not set
            $user->update([
                'avatar' => $user->avatar ?: $oauthUser->getAvatar(),
                'email' => $user->email ?: $oauthUser->getEmail(),
            ]);

            $request->session()->forget(['oauth_linking_mode', 'oauth_linking_user_id']);
            
            return redirect()->route('profile.index')
                ->with('message', ucfirst($provider) . ' has been successfully linked to your account!')
                ->with('refresh_oauth', true);

        } catch (Exception $e) {
            $request->session()->forget(['oauth_linking_mode', 'oauth_linking_user_id']);
            return redirect()->route('profile.index')
                ->withErrors(['oauth' => 'OAuth linking failed. Please try again.']);
        }
    }

    /**
     * Get OAuth provider display name
     */
    public static function getProviderDisplayName(string $provider): string
    {
        return match($provider) {
            'google' => 'Google',
            'github' => 'GitHub',
            'discord' => 'Discord',
            default => ucfirst($provider)
        };
    }

    /**
     * Get OAuth provider icon class
     */
    public static function getProviderIconClass(string $provider): string
    {
        return match($provider) {
            'google' => 'fab fa-google',
            'github' => 'fab fa-github',
            'discord' => 'fab fa-discord',
            default => 'fas fa-sign-in-alt'
        };
    }

    /**
     * Check registration restrictions
     */
    private function checkRegistrationRestrictions($oauthUser, Request $request)
    {
        $mode = config('oauth.registration.mode');
        $email = $oauthUser->getEmail();
        
        // Check if email is provided
        if (!$email) {
            return 'Email address is required for registration.';
        }
        
        // Check blocked domains and emails
        $blockedDomains = array_filter(config('oauth.registration.blocked_domains', []));
        $blockedEmails = array_filter(config('oauth.registration.blocked_emails', []));
        
        $emailDomain = substr(strrchr($email, '@'), 1);
        
        if (in_array($email, $blockedEmails) || in_array($emailDomain, $blockedDomains)) {
            return 'Registration is not allowed with this email address.';
        }
        
        // Check max accounts per email
        $maxAccounts = config('oauth.security.max_accounts_per_email', 1);
        if ($maxAccounts > 0) {
            $existingCount = User::where('email', $email)->count();
            if ($existingCount >= $maxAccounts) {
                return 'Maximum number of accounts reached for this email address.';
            }
        }
        
        // Mode-specific checks
        switch ($mode) {
            case 'whitelist':
                $allowedDomains = array_filter(config('oauth.registration.allowed_domains', []));
                if (!empty($allowedDomains) && !in_array($emailDomain, $allowedDomains)) {
                    return 'Registration is only allowed for specific email domains.';
                }
                break;
                
            case 'invite_only':
                if (!$request->has('invite_code')) {
                    return 'An invitation code is required to register.';
                }
                
                $invite = OAuthInvite::findValidInvite($request->invite_code, $email);
                if (!$invite) {
                    return 'Invalid or expired invitation code.';
                }
                break;
        }
        
        return true;
    }

    /**
     * Get OAuth provider button color
     */
    public static function getProviderButtonColor(string $provider): string
    {
        return match($provider) {
            'google' => 'danger',
            'github' => 'dark',
            'discord' => 'primary',
            default => 'secondary'
        };
    }

    /**
     * Unlink an OAuth provider from the current user
     */
    public function unlinkProvider(string $provider, Request $request): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->withErrors(['oauth' => 'You must be logged in to unlink OAuth providers.']);
        }

        if (!in_array($provider, $this->supportedProviders)) {
            return redirect()->route('profile.index')
                ->withErrors(['oauth' => 'Unsupported OAuth provider.']);
        }

        $user = Auth::user();
        
        // Check if user has this provider linked
        if (!$user->hasOAuthProvider($provider)) {
            return redirect()->route('profile.index')
                ->withErrors(['oauth' => ucfirst($provider) . ' is not linked to your account.']);
        }

        // Prevent unlinking if it's the only authentication method
        $linkedProviders = $user->getLinkedProviders();
        $hasPassword = !empty($user->password) && $user->password !== bin2hex(random_bytes(32));
        
        if (count($linkedProviders) === 1 && !$hasPassword) {
            return redirect()->route('profile.index')
                ->withErrors(['oauth' => 'Cannot unlink ' . ucfirst($provider) . ' as it is your only authentication method. Please set a password first.']);
        }

        // Unlink the provider
        if ($user->unlinkOAuthProvider($provider)) {
            return redirect()->route('profile.index')
                ->with('message', ucfirst($provider) . ' has been successfully unlinked from your account.')
                ->with('refresh_oauth', true);
        } else {
            return redirect()->route('profile.index')
                ->withErrors(['oauth' => 'Failed to unlink ' . ucfirst($provider) . '. Please try again.']);
        }
    }
}