<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerAnalytic extends Model
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
    protected $table = 'serverAnalytics';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ONLINE',
        'SERVERS',
        'TIME',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'TIME' => 'float',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
