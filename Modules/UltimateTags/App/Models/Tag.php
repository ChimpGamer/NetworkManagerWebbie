<?php

namespace Modules\UltimateTags\App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $connection = 'ultimatetags';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'tag',
        'description',
        'server',
        'permission',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [

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
