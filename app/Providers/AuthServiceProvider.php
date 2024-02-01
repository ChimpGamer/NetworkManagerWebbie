<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //

        $permissions = [
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

            'edit_settings', // To create
        ];
        // Set Permissions
        foreach ($permissions as $permission) {
            Gate::define($permission, function (User $user) use ($permission) {
                return $user->hasPermissions($permission);
            });
        }
    }
}
