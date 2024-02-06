<?php

namespace App\Models\Permissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GroupMember extends Model
{
    use HasFactory;

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
    protected $table = 'permissions_playergroups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'playeruuid',
        'groupid',
        'server',
        'expires',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'groupid' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expires'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function group(): HasOne
    {
        return $this->hasOne(Group::class, 'id', 'groupid');
    }

    public function permissionPlayer(): HasOne
    {
        return $this->hasOne(PermissionPlayer::class, 'uuid', 'playeruuid');
    }

    function willExpire(): bool
    {
        return $this->expires != null;
    }

    function hasExpired(): bool
    {
        return $this->willExpire() && $this->expires->isPast();
    }
}
