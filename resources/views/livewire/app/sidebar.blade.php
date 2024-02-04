<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
    <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
            <a href="/"
               class="list-group-item list-group-item-action py-2 ripple @if(Route::currentRouteName() == 'home') active @endif"
               aria-current="true">
                <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Dashboard</span>
            </a>
            @can('view_analytics')
                <a href="/analytics"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'analytics')) active @endif"><i
                        class="fas fa-chart-line fa-fw me-3"></i><span>Analytics</span></a>
            @endcan
            @can('view_players')
                <a href="/players" class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'players')) active @endif">
                    <i class="fas fa-users fa-fw me-3"></i><span>Players</span>
                </a>
            @endcan
            @can('view_punishments')
                <a href="/punishments"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'punishments')) active @endif">
                    <i class="fas fa-lock fa-fw me-3"></i><span>Punishments</span>
                </a>
            @endcan
            @can('view_announcements')
                <a href="/announcements"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'announcements')) active @endif">
                    <i class="fas fa-bullhorn fa-fw me-3"></i><span>Announcements</span>
                </a>
            @endcan
            @can('view_servers')
                <a href="/servers"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'servers')) active @endif">
                    <i class="fas fa-server fa-fw me-3"></i><span>Servers</span>
                </a>
            @endcan
            @can('view_pre_punishments')
                <a href="/punishment_templates"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'punishment_templates')) active @endif">
                    <i class="fas fa-file-lines fa-fw me-3"></i><span>Punishment Templates</span>
                </a>
            @endcan
            @can('view_languages')
                <a href="/languages"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'languages')) active @endif">
                    <i class="fas fa-language fa-fw me-3"></i><span>Languages</span>
                </a>
            @endcan
            @can('view_permissions')
                <a href="/permissions"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'permissions')) active @endif">
                    <i class="fas fa-lock fa-fw me-3"></i><span>Permissions</span>
                </a>
            @endcan
            @can('view_filter')
                <a href="/filter"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'filter')) active @endif">
                    <i class="fas fa-filter-circle-xmark fa-fw me-3"></i><span>Filter</span>
                </a>
            @endcan
            @can('view_commandblocker')
                <a href="/commandblocker"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'commandblocker')) active @endif">
                    <i class="fas fa-ban fa-fw me-3"></i><span>Command Blocker</span>
                </a>
            @endcan
            @can('view_helpop')
                <a href="/helpop"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'helpop')) active @endif">
                    <i class="fas fa-question fa-fw me-3"></i><span>HelpOP</span>
                </a>
            @endcan
            @can('view_chat')
                <a href="/chat"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'chat')) active @endif">
                    <i class="fas fa-comments fa-fw me-3"></i><span>Chat</span>
                </a>
            @endcan
            @can('view_network')
                <a href="/motd"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'motd')) active @endif">
                    <i class="fas fa-message fa-fw me-3"></i><span>MOTD</span>
                </a>
                <a href="/settings"
                   class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'settings')) active @endif">
                    <i class="fas fa-gear fa-fw me-3"></i><span>Settings</span>
                </a>
                @can('manage_groups_and_accounts')
                    <a href="/accounts"
                       class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'accounts')) active @endif">
                        <i class="fas fa-users fa-fw me-3"></i><span>Groups & Accounts</span>
                    </a>
                @endcan
            @endcan
        </div>
    </div>
</nav>
