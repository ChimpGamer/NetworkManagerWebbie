@extends('layouts.app')

@section('content')
    <p>Addon: {!! config('ultimatejqmessages.name') !!}</p>

    @livewire('ultimatejqmessages::show-join-quit-messages')
@endsection
