@extends('layouts.app')

@section('content')
    <p>Addon: {!! config('ultimatetags.name') !!}</p>

    @livewire('ultimatetags::show-tags')
@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#deleteTagModal').modal('hide');
            $('#editTagModal').modal('hide');
            $('#addTagModal').modal('hide');
        });
    </script>
@endsection
