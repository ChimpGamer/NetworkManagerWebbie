<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Group extends Model
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
    protected $table = 'accountgroups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'administrator',
        'view_analytics',
        'view_network',
        'view_players',
        'view_chat',
        'edit_chat',
        'view_filter',
        'edit_filter',
        'view_punishments',
        'edit_punishments',
        'view_accounts',
        'edit_accounts',
        'view_addons',
        'view_tickets',
        'view_ticket',
        'respond_ticket',
        'close_ticket',
        'assign_ticket',
        'view_chatlogs',
        'edit_chatlog',
        'view_permissions',
        'edit_permissions',
        'view_commandblocker',
        'edit_commandblocker',
        'view_announcements',
        'edit_announcements',
        'view_reports',
        'edit_reports',
        'view_helpop',
        'edit_helpop',
        'view_tags',
        'edit_tags',
        'view_servers',
        'edit_servers',
        'view_languages',
        'edit_languages',
        'view_pre_punishments',
        'edit_pre_punishments',
        'show_ip',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'administrator' => 'boolean',

        'view_analytics' => 'boolean',
        'view_punishments' => 'boolean',
        'view_network' => 'boolean',
        'view_players' => 'boolean',
        'view_chat' => 'boolean',
        'view_filter' => 'boolean',
        'edit_filter' => 'boolean',
        'edit_punishments' => 'boolean',
        'show_ip' => 'boolean',
        'edit_accounts' => 'boolean',
        'view_addons' => 'boolean',
        'view_tickets' => 'boolean',
        'view_ticket' => 'boolean',
        'respond_ticket' => 'boolean',
        'close_ticket' => 'boolean',
        'assign_ticket' => 'boolean',
        'view_chatlogs' => 'boolean',
        'view_permissions' => 'boolean',
        'view_commandblocker' => 'boolean',
        'edit_commandblocker' => 'boolean',
        'view_announcements' => 'boolean',
        'edit_announcements' => 'boolean',
        'view_reports' => 'boolean',
        'edit_reports' => 'boolean',
        'view_helpop' => 'boolean',
        'edit_chat' => 'boolean',
        'view_tags' => 'boolean',
        'edit_tags' => 'boolean',
        'view_servers' => 'boolean',
        'edit_servers' => 'boolean',
        'edit_chatlog' => 'boolean',
        'view_languages' => 'boolean',
        'edit_languages' => 'boolean',
        'edit_permissions' => 'boolean',
        'edit_helpop' => 'boolean',
        'view_pre_punishments' => 'boolean',
        'edit_pre_punishments' => 'boolean',
        'view_accounts' => 'boolean',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The users for the model.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'usergroup', 'name');
    }

    /**
     * Determine if this group is considered administrator.
     *
     * @return bool
     */
    public function isAdministrator(): bool
    {
        return $this->administrator;
    }

    /**
     * Determine if the group contains the given permissions.
     *
     * @param string|array $permissions
     * @return bool
     */
    public function hasPermissions(string|array $permissions): bool
    {
        $permissions = $this->mapPermissionsToValues(Arr::wrap($permissions));

        return ! in_array(false, $permissions);
    }

    /**
     * Determine if the group contains any of the given permissions.
     *
     * @param string|array $permissions
     * @return bool
     */
    public function hasAnyPermissions(string|array $permissions): bool
    {
        $permissions = $this->mapPermissionsToValues(Arr::wrap($permissions));

        return in_array(true, $permissions);
    }

    /**
     * Map the given permissions to their values.
     *
     * @param array $permissions
     * @return array
     */
    public function mapPermissionsToValues(array $permissions): array
    {
        return array_map(function (string $permission) {
            return $this->isAdministrator() || $this->getAttributeValue($permission);
        }, $permissions);
    }
}
