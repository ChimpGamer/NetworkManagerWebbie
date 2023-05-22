<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

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
    public $timestamps = false;

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
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'username';
    }

    /**
     * Get the password of the user.
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->getAttribute('password');
    }


    /**
     * Set the password attribute.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

     /**
     * The group for the model.
     *
     * @return HasOne
     */
    public function group(): HasOne
    {
        return $this->hasOne(Group::class, 'name', 'usergroup');
    }

    /**
     * Determine if the user has the given permissions.
     *
     * This is an AND check.
     *
     * @param array|string $permissions
     * @return bool
     */
    public function hasPermissions(array|string $permissions): bool
    {
        return $this->group->hasPermissions($permissions);
    }

    /**
     * Determine if the user has any of the given permissions.
     *
     * This is an OR check.
     *
     * @param array|string $permissions
     * @return bool
     */
    public function hasAnyPermissions(array|string $permissions): bool
    {
        return $this->group->hasAnyPermissions($permissions);
    }

    public function getUUID() {
        $player = DB::table('players')
            ->select('uuid')
            ->where('username', $this->username)
            ->first();
        return $player?->uuid;
    }
}
