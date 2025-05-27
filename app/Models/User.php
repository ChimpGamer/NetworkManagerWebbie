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
}
