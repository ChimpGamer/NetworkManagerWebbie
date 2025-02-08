@extends('layouts.app')

@section('content')

    <div>
        @livewire('servers.show-servers')
        <hr class="hr">
        <br>
        @livewire('servers.show-server-groups')
    </div>

@endsection
