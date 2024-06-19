<?php

namespace App\Models\Player;

use App\Helpers\CountryUtils;
use App\Helpers\TimeUtils;
use App\Models\ProtocolVersion;
use App\Models\Tag;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Player extends Model
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
    protected $table = 'players';

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
        'username',
        'nickname',
        'language',
        'tagid',
        'ip',
        'country',
        'version',
        'firstlogin',
        'lastlogin',
        'lastlogout',
        'online',
        'playtime',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'language' => 'integer',
        'tagid' => 'integer',

        'online' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'firstlogin',
        'lastlogin',
        'lastlogout',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function tag(): HasOne
    {
        return $this->hasOne(Tag::class, 'id', 'tagid');
    }

    protected function playtime(): Attribute
    {
        return Attribute::make(get: fn (int $value) => TimeUtils::millisToReadableFormat($value));
    }

    public function fullCountry(): string
    {
        return CountryUtils::countryCodeToCountry($this->country);
    }

    public function version(): Attribute
    {
        return Attribute::make(get: fn (int $value) => ProtocolVersion::tryFrom($value) ?? ProtocolVersion::SNAPSHOT);
    }

    public static function getName($uuid)
    {
        if ($uuid == null) {
            return null;
        }
        if ($uuid == 'f78a4d8d-d51b-4b39-98a3-230f2de0c670') {
            return 'CONSOLE';
        }
        $player = Player::select('username')
            ->where('uuid', $uuid)
            ->first();
        if ($player == null) {
            return $player;
        }

        return $player->username;
    }

    public static function getIP($uuid): ?string
    {
        $player = Player::select('ip')
            ->where('uuid', $uuid)
            ->first();
        if ($player == null) {
            return null;
        }

        return $player->ip;
    }

    public static function getMostPlayedVersions()
    {
        $total = DB::table('players')
            ->distinct()
            ->count();

        $result = DB::table('players')
            ->select(DB::raw('DISTINCT(version) as version, count(*) AS count'))
            ->orderBy('count', 'desc')
            ->groupBy('version')
            ->get();

        return $result->map(function ($item) use ($total) {
            $percentage = 0;
            if ($total != 0) {
                $percentage = $item->count / $total * 100;
            }
            $protocolVersion = ProtocolVersion::tryFrom($item->version);
            $version = $protocolVersion == null ? 'snapshot' : $protocolVersion->name();

            return [
                'version' => $version,
                'players' => $item->count,
                'percentage' => number_format($percentage, 2, '.', ' '),
            ];
        });
    }

    public static function getMostUsedVirtualHosts()
    {
        $total = DB::table('logins')
            ->distinct('uuid', 'vhost')
            ->count();

        $result = DB::table('logins')
            ->select(DB::raw('DISTINCT(vhost) as vhost, count(DISTINCT(uuid)) AS count'))
            ->orderBy('count', 'desc')
            ->groupBy('vhost')
            ->get();

        return $result->map(function ($item) use ($total) {
            $percentage = 0;
            if ($total != 0) {
                $percentage = $item->count / $total * 100;
            }

            return [
                'vhost' => ($item->vhost == null ? 'Unknown' : $item->vhost),
                'players' => $item->count,
                'percentage' => number_format($percentage, 2, '.', ' '),
            ];
        });
    }

    public function getAveragePlaytime(): string
    {
        $data = DB::table('sessions')
            ->select('time')
            ->where('uuid', $this->uuid)
            ->get();
        $count = $data->count();

        $time = 0;
        foreach ($data as $item) {
            $time += $item->time;
        }
        if ($count == 0) {
            $averagePlaytime = 0;
        } else {
            $averagePlaytime = $time / $count;
        }
        try {
            return CarbonInterval::millisecond($averagePlaytime)->cascade()->forHumans();
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function getAverageDailyLogin(): string
    {
        $data = DB::table('sessions')
            ->select('start')
            ->where('uuid', $this->uuid)
            ->groupBy('uuid')
            ->avg('start');

        return date('H:i:s', $data / 1000);
    }

    public function getSessions(): \Illuminate\Support\Collection
    {
        return DB::table('sessions')
            ->where('uuid', $this->uuid)
            ->get();
    }

    public function getAltAccounts(): Collection
    {
        return Player::where('ip', $this->ip)->where('uuid', '!=', $this->uuid)->get();
    }

    public function getMostUsedVersions(): array
    {
        $total = DB::table('logins')
            ->where('version', '<>', 0)
            ->where('uuid', $this->uuid)
            ->count();

        $data = DB::table('logins')
            ->selectRaw('DISTINCT(version) as version, count(version) AS versionUse')
            ->where('version', '<>', 0)
            ->where('uuid', $this->uuid)
            ->groupBy('version')
            ->get();

        $labels = [];
        $values = [];
        foreach ($data as $item) {
            $protocolVersion = ProtocolVersion::tryFrom($item->version);
            $version = $protocolVersion == null ? 'snapshot' : $protocolVersion->name();
            $labels[] = $version;
            $values[] = $item->versionUse / $total;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    public function getTimestampFormatted($timestamp): string
    {
        return date('d-m-Y H:i:s', $timestamp / 1000);
    }
}
