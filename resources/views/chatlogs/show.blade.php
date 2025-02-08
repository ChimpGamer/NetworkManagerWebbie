@extends('layouts.app')

@section('content')

    <div>
        @livewire('chat-log.show-chat-log', ['chatLog' => $chatLog])
    </div>

@endsection
