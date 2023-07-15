<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
    <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
            <a href="/" class="list-group-item list-group-item-action py-2 ripple @if(Route::currentRouteName() == 'home') active @endif" aria-current="true">
                <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Dashboard</span>
            </a>
            <a href="analytics" class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'analytics')) active @endif"><i
                    class="fas fa-chart-line fa-fw me-3"></i><span>Analytics</span></a>
            <a href="#" class="list-group-item list-group-item-action py-2 ripple">
                <i class="fas fa-chart-area fa-fw me-3"></i><span>Players</span>
            </a>
            <a href="punishments" class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'punishments')) active @endif">
                <i class="fas fa-lock fa-fw me-3"></i><span>Punishments</span>
            </a>
            <a href="announcements" class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'announcements')) active @endif">
                <i class="fas fa-lock fa-fw me-3"></i><span>Announcements</span>
            </a>
            <a href="servers" class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'servers')) active @endif">
                <i class="fas fa-lock fa-fw me-3"></i><span>Servers</span>
            </a>
            <a href="punishment_templates" class="list-group-item list-group-item-action py-2 ripple @if(Str::startsWith(Route::currentRouteName(), 'punishment_templates')) active @endif">
                <i class="fas fa-lock fa-fw me-3"></i><span>Punishment Templates</span>
            </a>
        </div>
    </div>
</nav>
