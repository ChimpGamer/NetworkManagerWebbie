@extends('layouts.app')

@section('content')
    <p>Addon: {!! config('ultimatetags.name') !!}</p>

    @livewire('ultimatetags::show-tags')
@endsection
