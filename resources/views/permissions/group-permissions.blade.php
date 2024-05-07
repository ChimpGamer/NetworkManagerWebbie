@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-group-permissions', ['group' => $group])
    </div>

@endsection
