<?php

namespace App\Models\Player;

use App\Models\ProtocolVersion;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
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
    protected $table = 'logins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'ip',
        'vhost',
        'version',
        'time',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function version(): Attribute
    {
        return Attribute::make(get: fn (int $value) => ProtocolVersion::tryFrom($value) ?? ProtocolVersion::SNAPSHOT);
    }
}
