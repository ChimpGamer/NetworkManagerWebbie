<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-mdb-theme="{{ config('app.theme') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">

    <title>
        {{ config('app.name') }}
    </title>

    <!-- Google Fonts Roboto -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"/>
    <!-- Google Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"/>

    <link href="{{ asset('css/mdb.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/labels.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet"/>

    <!-- Tippy -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>
<header>
    <div>
        @livewire('navbar')
        @livewire('sidebar')
    </div>
</header>

<!--Main Navigation-->
<main style="margin-top: 58px;">
    <div class="container my-5">
        @yield('content')
    </div>
</main>

<script src="{{ asset('js/mdb.umd.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>

<!-- Alpine Tooltip -->
<script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-tooltip@1.x.x/dist/cdn.min.js" defer></script>

<!-- Alpine Truncate -->
<script src="https://unpkg.com/@alpine-collective/toolkit-truncate@1.0.2/dist/cdn.min.js" defer></script>

@yield('script')
</body>
</html>
