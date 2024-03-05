@extends('layouts.app')

@section('content')

    <div>
        @livewire('tickets.show-ticket', ['ticket' => $ticket])
    </div>

@endsection
