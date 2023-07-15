<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Player extends Model
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
    protected $table = 'players';

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
        'playtime'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'language' => 'integer',
        'tagid' => 'integer',
        'version' => 'integer',

        'online' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'firstlogin',
        'lastlogin',
        'lastlogout'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function getName($uuid) {
        if ($uuid == 'f78a4d8d-d51b-4b39-98a3-230f2de0c670') return 'CONSOLE';
        $player = Player::select('username')
        ->where('uuid', $uuid)
        ->first();
        if ($player == null) return $player;
        return $player->username;
    }

    public static function getMostPlayedVersions() {
        $total = DB::table('players')
        ->distinct('uuid')
        ->count();

        $result = DB::table("players")
        ->select(DB::raw('DISTINCT(version) as version, count(*) AS count'))
        ->orderBy("count","desc")
        ->groupBy("version")
        ->get();

        return $result->map(function($item) use($total) {
            return [
                'version' => $item->version,
                'players' => $item->count,
                'percentage' => number_format((($item->count / $total) * 100), 2, '.', ' ')
            ];
        });
    }

    public static function getMostUsedVirtualHosts() {
        $total = DB::table('logins')
        ->distinct('uuid')
        ->count();

        $result = DB::table("logins")
        ->select(DB::raw('DISTINCT(vhost) as vhost, count(*) AS count'))
        ->orderBy("count","desc")
        ->groupBy("vhost")
        ->get();

        return $result->map(function($item) use($total) {
            return [
                'vhost' => ($item->vhost == null ? 'Unknown' : $item->vhost),
                'players' => $item->count,
                'percentage' => number_format((($item->count / $total) * 100), 2, '.', ' ')
            ];
        });
    }
}
