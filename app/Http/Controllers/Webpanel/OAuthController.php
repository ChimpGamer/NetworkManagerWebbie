<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        $this->middleware('guest')->except('logout');
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
            
            // Try to find existing user by OAuth provider
            $user = User::findByOAuth($provider, $oauthUser->getId());
            
            if ($user) {
                // Existing user - just update and login
                $user->update([
                    'avatar' => $oauthUser->getAvatar(),
                    'last_login' => now(),
                ]);
                
                // Check if user is active
                if (!$user->is_active) {
                    return redirect()->route('auth.login')
                        ->withErrors(['oauth' => 'Your account has been deactivated. Please contact administrator.']);
                }
                
                Auth::login($user, true);
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

            // Create new user
            $user = User::createOrUpdateFromOAuth($provider, $userData);
            
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
            RateLimiter::hit($key, config('oauth.security.rate_limit.decay_minutes', 60) * 60);
            return redirect()->route('auth.login')
                ->withErrors(['oauth' => 'OAuth authentication failed. Please try again.']);
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
}