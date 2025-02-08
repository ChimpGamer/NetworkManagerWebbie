@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/motd.css')}}">
@endpush

@section('content')

    <div>
        @livewire('show-motd')
    </div>

@endsection
