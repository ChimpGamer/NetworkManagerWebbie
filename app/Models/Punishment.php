<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'active'
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

    public function getPlayerName() {
        return Player::getName($this->uuid);
    }

    public function getPunisherName() {
        return Player::getName($this->punisher);
    }

    public function getTimeFormatted() {
        return date('d-m-Y H:i:s', $this->time / 1000);
    }
}
