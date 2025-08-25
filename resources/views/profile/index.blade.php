@extends('layouts.app')

@section('content')

    <div class="row gy-4">
        <div class="col-md-6">
            @livewire('profile.show-change-password')
        </div>
        <div class="col-md-6">
            @livewire('profile.show-oauth-providers')
        </div>
    </div>

@endsection
