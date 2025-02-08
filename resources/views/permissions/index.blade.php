@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-groups')
        <hr class="hr">
        <br>
        @livewire('permissions.show-players')
    </div>

@endsection
