<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserOAuthProvider extends Model
{
    use HasFactory;

    /**
     * The connection name for the model.
     */
    protected $connection = 'manager';

    /**
     * The table associated with the model.
     */
    protected $table = 'user_oauth_providers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'provider_email',
        'provider_avatar',
        'provider_data',
        'linked_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'provider_data' => 'array',
        'linked_at' => 'datetime',
    ];

    /**
     * Get the user that owns this OAuth provider.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Find a user OAuth provider by provider and provider ID.
     */
    public static function findByProvider(string $provider, string $providerId): ?self
    {
        return static::where('provider', $provider)
                    ->where('provider_id', $providerId)
                    ->first();
    }

    /**
     * Check if a provider is already linked to a user.
     */
    public static function isProviderLinked(string $provider, string $providerId): bool
    {
        return static::where('provider', $provider)
                    ->where('provider_id', $providerId)
                    ->exists();
    }

    /**
     * Get all providers for a user.
     */
    public static function getProvidersForUser(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('user_id', $userId)->get();
    }

    /**
     * Check if a user has a specific provider linked.
     */
    public static function userHasProvider(int $userId, string $provider): bool
    {
        return static::where('user_id', $userId)
                    ->where('provider', $provider)
                    ->exists();
    }

    /**
     * Get the display name for the provider.
     */
    public function getDisplayNameAttribute(): string
    {
        return match($this->provider) {
            'google' => 'Google',
            'github' => 'GitHub',
            'discord' => 'Discord',
            default => ucfirst($this->provider),
        };
    }

    /**
     * Get the icon class for the provider.
     */
    public function getIconAttribute(): string
    {
        return match($this->provider) {
            'google' => 'fab fa-google',
            'github' => 'fab fa-github',
            'discord' => 'fab fa-discord',
            default => 'fas fa-link',
        };
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->linked_at) {
                $model->linked_at = now();
            }
        });
    }
}
