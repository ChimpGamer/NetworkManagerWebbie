<nav id="main-navbar" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#sidebarMenu"
                aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Brand -->
        <a class="navbar-brand">
            <img src="/images/full_logo.png" height="25" alt="NetworkManager Logo"
                 loading="lazy"/>
        </a>

        <!-- Right links -->
        <ul class="navbar-nav ms-auto d-flex flex-row">
            <!-- Avatar -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#"
                   id="navbarDropdownMenuLink" role="button" data-mdb-dropdown-init aria-expanded="false">
                    @if (Auth::check())
                        <img src="https://minotar.net/helm/{{ Auth::user()->getUUID() }}" class="rounded-circle"
                             height="22" alt="Avatar" loading="lazy"/>
                    @else
                        <img src="https://minotar.net/helm/MHF_Steve" class="rounded-circle" height="22" alt="Avatar"
                             loading="lazy"/>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                    <li>
                        <div class="dropdown-header" style="color: inherit !important;">
                            <p style="font-size:0.875rem;line-height: 1.25rem;margin:0;">Signed in as</p>
                            <p style="font-weight: 500; font-size:0.875rem;line-height: 1.25rem;margin:0;">{{ Auth::user()->username }}</p>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="/profile"><i class="fa-solid fa-user"></i> My profile</a>
                    </li>
                    {{--<li>
                        <a class="dropdown-item" href="#">Settings</a>
                    </li>--}}
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item" type="submit"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- Container wrapper -->
</nav>
