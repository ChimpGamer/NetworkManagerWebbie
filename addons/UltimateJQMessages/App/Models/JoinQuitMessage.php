<?php

namespace Addons\UltimateJQMessages\App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinQuitMessage extends Model
{
    protected $connection = 'ultimatejqmessages';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'join_quit_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'message',
        'permission',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => JoinQuitMessageType::class,
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [

    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
