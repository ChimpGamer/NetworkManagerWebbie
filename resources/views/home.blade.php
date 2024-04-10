@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-users text-info fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{ $total_players }}</h3>
                            <p class="mb-0">Total Players</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-user-check text-warning fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{ $today_online_players }}</h3>
                            <p class="mb-0">Today Online Players</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-user-plus text-success fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{ $new_players }}</h3>
                            <p class="mb-0">Today New Players</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div class="align-self-center">
                            <i class="fas fa-user-clock text-danger fa-3x"></i>
                        </div>
                        <div class="text-end">
                            <h3>{{ $today_playtime }}</h3>
                            <p class="mb-0">Today Playtime</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in') }}
                    {{ Auth::user()->username }}!
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center py-3">
                    <h5 class="mb-0 text-center">
                        <strong>Player Statistics</strong>
                    </h5>
                </div>
                <div class="card-body">
                    @livewire('dashboard.player-statistics-chart', ['lazy' => true])
                </div>
            </div>
        </div>
    </div>
@endsection
