@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-group-suffixes', ['group' => $group])
    </div>

@endsection
