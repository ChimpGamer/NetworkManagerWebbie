@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-player-permissions', ['player' => $player])
    </div>

@endsection
