@extends('layouts.app')

@section('content')

    <div>
        @livewire('tickets.show-ticket', ['ticket' => $ticket])
    </div>

@endsection

@section('script')
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
@endsection
