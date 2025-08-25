<?php

namespace App\Models;

use App\Models\Player\Player;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_login',
        'email_verified_at',
        'approved_at',
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
     * Check if user is authenticated via OAuth
     */
    public function isOAuthUser(): bool
    {
        return !empty($this->oauth_provider) && !empty($this->oauth_provider_id);
    }

    /**
     * Find user by OAuth provider and ID
     */
    public static function findByOAuth(string $provider, string $providerId): ?self
    {
        return static::where('oauth_provider', $provider)
                    ->where('oauth_provider_id', $providerId)
                    ->first();
    }

    /**
     * Create or update user from OAuth data
     */
    public static function createOrUpdateFromOAuth(string $provider, array $oauthUser): self
    {
        $user = static::findByOAuth($provider, $oauthUser['id']);
        
        if ($user) {
            // Update existing user
            $user->update([
                'email' => $oauthUser['email'] ?? $user->email,
                'avatar' => $oauthUser['avatar'] ?? $user->avatar,
                'last_login' => now(),
            ]);
        } else {
            // Create new user
            $user = static::create([
                'username' => $oauthUser['nickname'] ?? $oauthUser['name'] ?? 'user_' . time(),
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
