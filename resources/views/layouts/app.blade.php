<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-mdb-theme="{{ config('app.theme') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name') }}
    </title>

    <!-- Google Fonts Roboto -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"/>
    <!-- Google Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet"/>

    <link href="{{ asset('css/mdb.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/labels.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet"/>

    <script src="{{ asset('js/mdb.umd.min.js') }}" defer></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script defer src="https://unpkg.com/@popperjs/core@2"></script>

    @stack('styles')
</head>

<body>
<!--Main Navigation-->
<main style="margin-top: 58px;">
    <div class="container my-5">
        @yield('content')
    </div>
</main>

@yield('script')
</body>

<header>
    <div>
        <livewire:sidebar/>
        <livewire:navbar/>
    </div>
</header>

</html>
