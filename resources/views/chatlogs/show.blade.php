@extends('layouts.app')

@section('content')

    <div>
        @livewire('show-chatlog', ['chatLog' => $chatLog])
    </div>

@endsection
