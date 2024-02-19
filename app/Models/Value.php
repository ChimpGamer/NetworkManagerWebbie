<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
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
    protected $table = 'values';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'variable';
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'variable',
        'value',
        'version',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected array $booleanSettings = [
        'module_announcements',
        'module_antiad',
        'module_anticaps',
        'module_antispam',
        'module_commandblocker',
        'module_filter',
        'module_helpop',
        'module_maxplayers',
        'module_notifications',
        'module_party',
        'module_permissions_bungee',
        'module_permissions_spigot',
        'module_pre_punishments',
        'module_proxy_only',
        'module_punishments',
        'module_reports',
        'module_servermanager',
        'module_slashserver',
        'module_tabheader',
        'module_tags',
        'module_tickets',
        'module_tps',

        'punishments_remind_warnings_on_join',
        'setting_antiad_adminchat',
        'setting_antiad_party',
        'setting_antiad_staffchat',
        'setting_anticaps_adminchat',
        'setting_anticaps_lowercase_caps',
        'setting_anticaps_party',
        'setting_anticaps_send_warning',
        'setting_anticaps_staffchat',
        'setting_antispam_adminchat',
        'setting_antispam_party',
        'setting_antispam_staffchat',
        'setting_antiswear_adminchat',
        'setting_antiswear_party',
        'setting_antiswear_staffchat',
        'setting_antiswear_warn_if_replace',
        'setting_maxplayers_onemore_player',
        'setting_namecolors',
        'setting_punishments_ipban_ban_ip_only',
        'setting_punishments_require_reason',
        'setting_server_kickmessage',
        'setting_servermanager_force_logingroup',
        'setting_servermanager_kickmove',
        'setting_show_reports_onlogin',
        'setting_nickname_use_filter',
        'setting_notify_banned_player_join',
    ];

    public function isBooleanSetting(): bool
    {
        if (str_ends_with($this->variable, '_enabled')) {
            return true;
        }
        return in_array($this->variable, $this->booleanSettings);
    }

    public static function getValueByVariable(string $variable): mixed
    {
        return Value::where('variable', $variable)->first();
    }
}
