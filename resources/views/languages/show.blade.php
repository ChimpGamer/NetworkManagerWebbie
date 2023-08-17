@extends('layouts.app')

@section('content')

    <div>
        @livewire('languages.show-language', ['language' => $language])
    </div>

@endsection
