<?php

namespace App\Models\Chat;

use App\Helpers\TimeUtils;
use App\Models\Player\Player;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ChatLog extends Model
{
    use HasUuids;

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
    protected $table = 'chatlogs';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'creator',
        'tracked',
        'server',
        'time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'time' => 'int',
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

    public function creatorName(): string
    {
        return Player::getName($this->creator);
    }

    public function trackedName(): string
    {
        return Player::getName($this->tracked);
    }

    public function timeFormatted(): string
    {
        return TimeUtils::formatTimestamp($this->time);
    }
}
