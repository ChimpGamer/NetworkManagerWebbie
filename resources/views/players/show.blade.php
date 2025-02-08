@extends('layouts.app')

@section('content')

    <div>
        @livewire('player.show-player', ['player' => $player])
    </div>

@endsection
