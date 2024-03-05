<?php

namespace App\Models\Tickets;

use App\Helpers\TimeUtils;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class Ticket extends Model
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
    protected $table = 'tickets_tickets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'creator',
        'title',
        'message',
        'creation',
        'priority',
        'last_answer',
        'last_update',
        'assigned_from',
        'assigned_to',
        'assigned_on',
        'closed_by',
        'closed_on',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'priority' => TicketPriority::class,
        'active' => 'boolean',
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

    public function ticketMessages(): HasOneOrMany
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id', 'id');
    }

    public function getCreatorName()
    {
        return Player::getName($this->creator);
    }

    public function getLastAnswerName()
    {
        return Player::getName($this->last_answer);
    }

    public function getClosedByName()
    {
        return Player::getName($this->last_answer);
    }

    public function getCreationFormatted(): string
    {
        $creation = Carbon::createFromTimestampMs($this->creation);
        if ($creation->diffInWeeks() <= 1) {
            return $creation->diffForHumans();
        } else {
            return TimeUtils::formatTimestamp($this->creation);
        }
    }

    public function getLastUpdateFormatted(): string
    {
        return TimeUtils::formatTimestamp($this->last_update);
    }
}
