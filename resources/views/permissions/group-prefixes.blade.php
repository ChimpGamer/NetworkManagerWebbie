@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-group-prefixes', ['group' => $group])
    </div>

@endsection
