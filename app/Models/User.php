<?php

namespace App\Models;

use App\Models\Player\Player;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'manager';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'usergroup',
        'notifications',
        'last_login',
        'is_active',
        'oauth_provider',
        'oauth_provider_id',
        'email',
        'avatar',
        'email_verified_at',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'notifications' => 'array',
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'notifications' => '[]',
    ];

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName(): string
    {
        return 'username';
    }

    /**
     * Get the password of the user.
     */
    public function getAuthPassword(): string
    {
        return $this->getAttribute('password');
    }

    /**
     * Set the password attribute.
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * The group for the model.
     */
    public function group(): HasOne
    {
        return $this->hasOne(Group::class, 'name', 'usergroup');
    }

    /**
     * Get all OAuth providers linked to this user.
     */
    public function oauthProviders(): HasMany
    {
        return $this->hasMany(UserOAuthProvider::class);
    }

    /**
     * Determine if the user has the given permissions.
     *
     * This is an AND check.
     */
    public function hasPermissions(array|string $permissions): bool
    {
        return $this->group->hasPermissions($permissions);
    }

    /**
     * Determine if the user has any of the given permissions.
     *
     * This is an OR check.
     */
    public function hasAnyPermissions(array|string $permissions): bool
    {
        return $this->group->hasAnyPermissions($permissions);
    }

    public function getUUID(): ?string
    {
        return Player::getUUID($this->username);
    }

    /**
     * Check if user is authenticated via OAuth (legacy single provider or new multiple providers)
     */
    public function isOAuthUser(): bool
    {
        // Check legacy single provider
        if (!empty($this->oauth_provider) && !empty($this->oauth_provider_id)) {
            return true;
        }
        
        // Check new multiple providers
        return $this->oauthProviders()->exists();
    }

    /**
     * Find user by OAuth provider and ID (supports both legacy and new system)
     */
    public static function findByOAuth(string $provider, string $providerId): ?self
    {
        // First check legacy single provider system
        $user = static::where('oauth_provider', $provider)
                     ->where('oauth_provider_id', $providerId)
                     ->first();
        
        if ($user) {
            return $user;
        }
        
        // Check new multiple providers system
        $oauthProvider = UserOAuthProvider::findByProvider($provider, $providerId);
        return $oauthProvider ? $oauthProvider->user : null;
    }

    /**
     * Check if user has a specific OAuth provider linked
     */
    public function hasOAuthProvider(string $provider): bool
    {
        // Check legacy single provider
        if ($this->oauth_provider === $provider) {
            return true;
        }
        
        // Check new multiple providers
        return UserOAuthProvider::userHasProvider($this->id, $provider);
    }

    /**
     * Get all linked OAuth providers for this user
     */
    public function getLinkedProviders(): array
    {
        $providers = [];
        
        // Include legacy single provider if exists
        if (!empty($this->oauth_provider)) {
            $providers[] = [
                'provider' => $this->oauth_provider,
                'provider_id' => $this->oauth_provider_id,
                'is_legacy' => true,
            ];
        }
        
        // Include new multiple providers
        foreach ($this->oauthProviders as $oauthProvider) {
            $providers[] = [
                'provider' => $oauthProvider->provider,
                'provider_id' => $oauthProvider->provider_id,
                'provider_email' => $oauthProvider->provider_email,
                'provider_avatar' => $oauthProvider->provider_avatar,
                'linked_at' => $oauthProvider->linked_at,
                'is_legacy' => false,
            ];
        }
        
        return $providers;
    }

    /**
     * Link a new OAuth provider to this user
     */
    public function linkOAuthProvider(string $provider, array $oauthUser): UserOAuthProvider
    {
        return $this->oauthProviders()->create([
            'provider' => $provider,
            'provider_id' => $oauthUser['id'],
            'provider_email' => $oauthUser['email'] ?? null,
            'provider_avatar' => $oauthUser['avatar'] ?? null,
            'provider_data' => $oauthUser,
            'linked_at' => now(),
        ]);
    }

    /**
     * Unlink an OAuth provider from this user
     */
    public function unlinkOAuthProvider(string $provider): bool
    {
        // Handle legacy single provider
        if ($this->oauth_provider === $provider) {
            return $this->update([
                'oauth_provider' => null,
                'oauth_provider_id' => null,
            ]);
        }
        
        // Handle new multiple providers
        return $this->oauthProviders()->where('provider', $provider)->delete() > 0;
    }

    /**
     * Create or update user from OAuth data
     */
    public static function createOrUpdateFromOAuth(string $provider, array $oauthUser): self
    {
        // First check if user already exists with this OAuth provider
        $user = static::findByOAuth($provider, $oauthUser['id']);
        
        if ($user) {
            // Update existing OAuth user
            $user->update([
                'email' => $oauthUser['email'] ?? $user->email,
                'avatar' => $oauthUser['avatar'] ?? $user->avatar,
                'last_login' => now(),
            ]);
        } else {
            // Check if a user with the same username already exists (from password registration)
            $username = $oauthUser['nickname'] ?? $oauthUser['name'] ?? 'user_' . time();
            $existingUser = static::where('username', $username)->first();
            
            if ($existingUser) {
                // Update existing password-registered user with OAuth credentials
                $existingUser->update([
                    'oauth_provider' => $provider,
                    'oauth_provider_id' => $oauthUser['id'],
                    'email' => $oauthUser['email'] ?? $existingUser->email,
                    'avatar' => $oauthUser['avatar'] ?? $existingUser->avatar,
                    'last_login' => now(),
                    'email_verified_at' => $existingUser->email_verified_at ?? now(),
                ]);
                $user = $existingUser;
            } else {
                // Create new user
                $user = static::create([
                    'username' => $username,
                    'password' => bin2hex(random_bytes(32)), // Generate random password for OAuth users
                    'email' => $oauthUser['email'],
                    'oauth_provider' => $provider,
                    'oauth_provider_id' => $oauthUser['id'],
                    'avatar' => $oauthUser['avatar'],
                    'usergroup' => 'default', // Set default usergroup
                    'is_active' => 1,
                    'approval_status' => config('oauth.registration.mode') === 'admin_approval' ? 'pending' : 'approved',
                    'approved_at' => config('oauth.registration.mode') === 'admin_approval' ? null : now(),
                    'last_login' => now(),
                    'email_verified_at' => now(),
                ]);
            }
        }
        
        return $user;
    }
    
    /**
     * Create user from OAuth data with multi-provider support
     */
    public static function createFromOAuth(string $provider, array $oauthUser): self
    {
        // Check if a user with the same username already exists (from password registration)
        $username = $oauthUser['nickname'] ?? $oauthUser['name'] ?? 'user_' . time();
        $existingUser = static::where('username', $username)->first();
        
        if ($existingUser) {
            // Link OAuth provider to existing user
            $existingUser->linkOAuthProvider($provider, $oauthUser);
            
            // Update user info
            $existingUser->update([
                'email' => $oauthUser['email'] ?? $existingUser->email,
                'avatar' => $oauthUser['avatar'] ?? $existingUser->avatar,
                'last_login' => now(),
                'email_verified_at' => $existingUser->email_verified_at ?? now(),
            ]);
            
            return $existingUser;
        }
        
        // Create new user
        $user = static::create([
            'username' => $username,
            'password' => bin2hex(random_bytes(32)), // Generate random password for OAuth users
            'email' => $oauthUser['email'],
            'avatar' => $oauthUser['avatar'],
            'usergroup' => 'default', // Set default usergroup
            'is_active' => 1,
            'approval_status' => config('oauth.registration.mode') === 'admin_approval' ? 'pending' : 'approved',
            'approved_at' => config('oauth.registration.mode') === 'admin_approval' ? null : now(),
            'last_login' => now(),
            'email_verified_at' => now(),
        ]);
        
        // Link OAuth provider to new user
        $user->linkOAuthProvider($provider, $oauthUser);
        
        return $user;
    }
    
    /**
     * Get the admin who approved this user
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    /**
     * Get users approved by this admin
     */
    public function approvedUsers()
    {
        return $this->hasMany(User::class, 'approved_by');
    }
    
    /**
     * Approve this user
     */
    public function approve(User $admin, string $notes = null): bool
    {
        return $this->update([
            'approval_status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => now(),
            'approval_notes' => $notes,
            'is_active' => 1,
        ]);
    }
    
    /**
     * Reject this user
     */
    public function reject(User $admin, string $notes = null): bool
    {
        return $this->update([
            'approval_status' => 'rejected',
            'approved_by' => $admin->id,
            'approved_at' => now(),
            'approval_notes' => $notes,
            'is_active' => 0,
        ]);
    }
    
    /**
     * Check if user is pending approval
     */
    public function isPendingApproval(): bool
    {
        return $this->approval_status === 'pending';
    }
    
    /**
     * Check if user is approved
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }
    
    /**
     * Check if user is rejected
     */
    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }
    
    /**
     * Scope for pending approval users
     */
    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }
    
    /**
     * Scope for approved users
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }
    
    /**
     * Scope for rejected users
     */
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }
}
