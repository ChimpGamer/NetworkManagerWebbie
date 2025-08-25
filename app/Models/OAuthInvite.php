<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OAuthInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'email',
        'created_by',
        'used_by',
        'expires_at',
        'used_at',
        'single_use',
        'max_uses',
        'used_count',
        'metadata',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'single_use' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Relationship: User who created the invite
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship: User who used the invite
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    /**
     * Generate a unique invite code
     */
    public static function generateCode(): string
    {
        do {
            $code = Str::random(16);
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Create a new invite
     */
    public static function createInvite(array $data): self
    {
        $invite = new self();
        $invite->code = self::generateCode();
        $invite->email = $data['email'] ?? null;
        $invite->created_by = $data['created_by'];
        $invite->expires_at = isset($data['expires_days']) 
            ? Carbon::now()->addDays($data['expires_days']) 
            : null;
        $invite->single_use = $data['single_use'] ?? true;
        $invite->max_uses = $data['max_uses'] ?? 1;
        $invite->metadata = $data['metadata'] ?? null;
        
        $invite->save();
        return $invite;
    }

    /**
     * Check if invite is valid
     */
    public function isValid(string $email = null): bool
    {
        // Check if expired
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // Check if already used up
        if ($this->used_count >= $this->max_uses) {
            return false;
        }

        // Check email restriction
        if ($this->email && $email && $this->email !== $email) {
            return false;
        }

        return true;
    }

    /**
     * Use the invite
     */
    public function use(int $userId): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $this->used_by = $userId;
        $this->used_at = Carbon::now();
        $this->used_count++;
        
        return $this->save();
    }

    /**
     * Find valid invite by code
     */
    public static function findValidInvite(string $code, string $email = null): ?self
    {
        $invite = self::where('code', $code)->first();
        
        if (!$invite || !$invite->isValid($email)) {
            return null;
        }
        
        return $invite;
    }

    /**
     * Scope: Active invites
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', Carbon::now());
        })->whereColumn('used_count', '<', 'max_uses');
    }

    /**
     * Scope: Expired invites
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', Carbon::now())
                    ->orWhereColumn('used_count', '>=', 'max_uses');
    }
}