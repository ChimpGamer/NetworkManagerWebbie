<?php

namespace App\Models\Player;

use App\Helpers\TimeUtils;
use App\Models\ProtocolVersion;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
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
    protected $table = 'sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'start',
        'end',
        'time',
        'ip',
        'version',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'version' => ProtocolVersion::class,
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function formatStart(): string
    {
        return TimeUtils::formatTimestamp($this->start);
    }

    public function formatEnd(): string
    {
        return TimeUtils::formatTimestamp($this->end);
    }

    public function formatTime(): string
    {
        return TimeUtils::millisToReadableFormat($this->time);
    }
}
