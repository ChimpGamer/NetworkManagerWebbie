@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-player-groups', ['player' => $player])
    </div>

@endsection
