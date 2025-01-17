<?php

namespace App\Models;

use App\Helpers\TimeUtils;
use App\Models\Player\Player;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Punishment extends Model
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
    protected $table = 'punishments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'type',
        'uuid',
        'punisher',
        'time',
        'end',
        'reason',
        'unbanreason',
        'ip',
        'server',
        'unbanner',
        'silent',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => PunishmentType::class,

        'silent' => 'boolean',
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

    public function player(): HasOne
    {
        return $this->hasOne(Player::class, 'uuid', 'uuid');
    }

    public function punisher(): HasOne
    {
        return $this->hasOne(Player::class, 'uuid', 'punisher');
    }

    public function ends(): Carbon
    {
        return TimeUtils::fromTimestampMs($this->end);
    }

    public function expires(): ?string
    {
        $end = $this->end;
        if ($end <= 0) {
            return 'Never';
        }

        return $this->ends()->fromNow();
    }

    public function expiresTooltip(): string
    {
        $end = $this->ends();
        if ($end->isPast()) {
            return 'Expired '.$end->ago();
        } else {
            return 'Expires in '.$end->fromNow();
        }
    }

    public function getEndFormatted(): string
    {
        return TimeUtils::formatTimestamp($this->end);
    }

    public function getPlayerName()
    {
        return Player::getName($this->uuid);
    }

    public function getPunisherName()
    {
        return Player::getName($this->punisher);
    }

    public function getTimeFormatted(): string
    {
        return TimeUtils::formatTimestamp($this->time);
    }

    public function getUnbannerName(): ?string
    {
        return Player::getName($this->unbanner);
    }
}
