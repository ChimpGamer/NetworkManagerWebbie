@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-group-members', ['group' => $group])
    </div>

@endsection
