@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/motd.css')}}">
@endpush

@section('content')

    <div>
        @livewire('show-motd')
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editMotdModal').modal('hide');
            $('#addMotdModal').modal('hide');
            $('#deleteMotdModal').modal('hide');
        });
    </script>
@endsection
