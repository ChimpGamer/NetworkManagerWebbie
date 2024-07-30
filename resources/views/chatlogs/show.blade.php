@extends('layouts.app')

@section('content')

    <div>
        @livewire('show-chat-log', ['chatLog' => $chatLog])
    </div>

@endsection
