@extends('layouts.app')

@section('content')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="row">
        <div class="col-6">
            @livewire('accounts.show-account-groups')
        </div>

        <div class="col-6">
            @livewire('accounts.show-accounts')
        </div>
    </div>

@endsection
