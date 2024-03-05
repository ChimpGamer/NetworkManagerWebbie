<?php

namespace App\Models\Tickets;

use App\Helpers\TimeUtils;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
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
    protected $table = 'tickets_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'ticket_id',
        'uuid',
        'message',
        'time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'ticket_id' => 'integer',
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

    public function getSenderName()
    {
        return Player::getName($this->uuid);
    }

    public function getTimeFormatted(): string
    {
        $creation = Carbon::createFromTimestampMs($this->time);
        if ($creation->diffInHours() <= 24) {
            return $creation->diffForHumans();
        } else {
            return TimeUtils::formatTimestamp($this->time);
        }
    }
}
