<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-body">
    <div class="sidebar-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
            <a href="/"

               data-mdb-ripple-init
               class="list-group-item list-group-item-action py-2 @if(Route::currentRouteName() == 'home') active @endif"
               aria-current="true">
                <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Dashboard</span>
            </a>
            @can('view_analytics')
                <a href="/analytics"

                   data-mdb-ripple-init
                   class="list-group-item list-group-item-action py-2 @if(request()->routeIs('analytics.*')) active @endif"><i
                        class="fas fa-chart-simple fa-fw me-3"></i><span>Analytics</span></a>
            @endcan
            @can('view_players')
                <a href="/players"

                   data-mdb-ripple-init
                   class="list-group-item list-group-item-action py-2 @if(request()->routeIs('players.*')) active @endif">
                    <i class="fas fa-users fa-fw me-3"></i><span>Players</span>
                </a>
            @endcan
            @can('view_punishments')
                @if($this->isModuleEnabled('module_punishments'))
                    <a href="/punishments"

                       data-mdb-ripple-init
                       class="list-group-item list-group-item-action py-2 @if(request()->routeIs('punishments.*')) active @endif">
                        <i class="fas fa-lock fa-fw me-3"></i><span>Punishments</span>
                    </a>
                @endif
            @endcan
            @can('view_announcements')
                @if($this->isModuleEnabled('module_announcements'))
                    <a href="/announcements"

                       data-mdb-ripple-init
                       class="list-group-item list-group-item-action py-2 @if(request()->routeIs('announcements.*')) active @endif">
                        <i class="fas fa-bullhorn fa-fw me-3"></i><span>Announcements</span>
                    </a>
                @endif
            @endcan
            @can('view_servers')
                @if($this->isModuleEnabled('module_servermanager'))
                    <a href="/servers"

                       data-mdb-ripple-init
                       class="list-group-item list-group-item-action py-2 @if(request()->routeIs('servers.*')) active @endif">
                        <i class="fas fa-server fa-fw me-3"></i><span>Servers</span>
                    </a>
                @endif
            @endcan
            @can('view_pre_punishments')
                @if($this->isModuleEnabled('module_pre_punishments'))
                    <a href="/punishment_templates"

                       data-mdb-ripple-init
                       class="list-group-item list-group-item-action py-2 @if(request()->routeIs('punishment_templates.*')) active @endif">
                        <i class="fas fa-file-lines fa-fw me-3"></i><span>Punishment Templates</span>
                    </a>
                @endif
            @endcan
            @can('view_languages')
                <a href="/languages"

                   data-mdb-ripple-init
                   class="list-group-item list-group-item-action py-2 @if(request()->routeIs('languages.*')) active @endif">
                    <i class="fas fa-language fa-fw me-3"></i><span>Languages</span>
                </a>
            @endcan
            @can('view_permissions')
                @if($this->isModuleEnabled('module_permissions_bungee'))
                    <a href="/permissions"

                       data-mdb-ripple-init
                       class="list-group-item list-group-item-action py-2 @if(Str::startsWith(Route::currentRouteName(), 'punishments')) active @endif">
                        <i class="fas fa-lock fa-fw me-3"></i><span>Permissions</span>
                    </a>
                @endif
            @endcan
            @can('view_filter')
                @if($this->isModuleEnabled('module_filter'))
                    <a href="/filter"

                       data-mdb-ripple-init
                       class="list-group-item list-group-item-action py-2 @if(request()->routeIs('filter.*')) active @endif">
                        <i class="fas fa-filter-circle-xmark fa-fw me-3"></i><span>Filter</span>
                    </a>
                @endif
            @endcan
            @can('view_commandblocker')
                @if($this->isModuleEnabled('module_commandblocker'))
                    <a href="/commandblocker"

                       data-mdb-ripple-init
                       class="list-group-item list-group-item-action py-2 @if(request()->routeIs('commandblocker.*')) active @endif">
                        <i class="fas fa-ban fa-fw me-3"></i><span>Command Blocker</span>
                    </a>
                @endif
            @endcan
            @can('view_helpop')
                @if($this->isModuleEnabled('module_helpop'))
                    <a href="/helpop"
                       data-mdb-ripple-init
                       class="list-group-item list-group-item-action py-2 @if(request()->routeIs('helpop.*')) active @endif">
                        <i class="fas fa-question fa-fw me-3"></i><span>HelpOP</span>
                    </a>
                @endif
            @endcan
            @can('view_chat')
                <a href="/chat"
                   data-mdb-ripple-init
                   class="list-group-item list-group-item-action py-2 @if(request()->routeIs('chat.*')) active @endif">
                    <i class="fas fa-comments fa-fw me-3"></i><span>Chat</span>
                </a>
            @endcan
            @can('view_chatlogs')
                <a href="/chatlogs"
                   data-mdb-ripple-init
                   class="list-group-item list-group-item-action py-2 @if(request()->routeIs('chatlogs.*')) active @endif">
                    <i class="fas fa-comments fa-fw me-3"></i><span>ChatLogs</span>
                </a>
            @endcan
            @can('view_tags')
                @if($this->isModuleEnabled('module_tags'))
                    <a href="/tags"

                       data-mdb-ripple-init
                       class="list-group-item list-group-item-action py-2 @if(request()->routeIs('tags.*')) active @endif">
                        <i class="fas fa-tags fa-fw me-3"></i><span>Tags</span>
                    </a>
                @endif
            @endcan
            @can('view_tickets')
                @if($this->isModuleEnabled('module_tickets'))
                    <a href="/tickets"

                       data-mdb-ripple-init
                       class="list-group-item list-group-item-action py-2 @if(Str::startsWith(Route::currentRouteName(), 'tickets')) active @endif">
                        <i class="fas fa-ticket fa-fw me-3"></i><span>Tickets</span>
                    </a>
                @endif
            @endcan
            @can('view_command_log')
                <a href="/commandlog"
                   data-mdb-ripple-init
                   class="list-group-item list-group-item-action py-2 @if(request()->routeIs('commandlog.*')) active @endif">
                    <i class="fas fa-terminal fa-fw me-3"></i><span>Command Log</span>
                </a>
            @endcan
            @can('view_network')
                @if(Auth::user()->can('view_motd') && $this->isModuleEnabled('motd_enabled'))
                    <a href="/motd"

                       data-mdb-ripple-init
                       class="list-group-item list-group-item-action py-2 @if(request()->routeIs('motd.*')) active @endif">
                        <i class="fas fa-message fa-fw me-3"></i><span>MOTD</span>
                    </a>
                @endif
                <a href="/serverstats"

                   data-mdb-ripple-init
                   class="list-group-item list-group-item-action py-2 @if(request()->routeIs('serverstats.*')) active @endif">
                   <i class="fas fa-chart-line fa-fw me-3"></i><span>Server Stats</span>
                </a>
                <a href="/settings"

                   data-mdb-ripple-init
                   class="list-group-item list-group-item-action py-2 @if(request()->routeIs('settings.*')) active @endif">
                    <i class="fas fa-gear fa-fw me-3"></i><span>Settings</span>
                </a>
                @can('manage_groups_and_accounts')
                    <a href="/accounts"

                       data-mdb-ripple-init
                       class="list-group-item list-group-item-action py-2 @if(request()->routeIs('accounts.*')) active @endif">
                        <i class="fas fa-users fa-fw me-3"></i><span>Groups & Accounts</span>
                    </a>
                @endcan
            @endcan

            @can('view_addons')
                @if(count(\Nwidart\Modules\Facades\Module::allEnabled()) > 0)
                    <a class="list-group-item list-group-item-action py-2"
                       aria-current="true"
                       data-mdb-collapse-init
                       data-mdb-ripple-init
                       href="#collapseAddons"
                       aria-expanded="false"
                       aria-controls="collapseAddons">
                        <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Addons</span>
                    </a>
                    <ul id="collapseAddons" class="collapse list-group list-group-flush">
                        @foreach(\Nwidart\Modules\Facades\Module::allEnabled() as $module)
                            <li class="list-group-item py-1">
                                <a href="/{{ $module->getLowerName() }}"
                                   data-mdb-ripple-init
                                   class="list-group-item list-group-item-action py-2 @if(request()->routeIs($module->getLowerName() . '.*')) active @endif">
                                    <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>{{ $module->getName() }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @endcan
        </div>
    </div>
</nav>
