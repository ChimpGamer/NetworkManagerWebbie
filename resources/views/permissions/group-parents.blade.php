@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-group-parents', ['group' => $group])
    </div>

@endsection
