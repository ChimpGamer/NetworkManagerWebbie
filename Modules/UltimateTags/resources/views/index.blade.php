@extends('layouts.app')

@section('content')
    <h1>Hello World</h1>

    <p>Addon: {!! config('ultimatetags.name') !!}</p>

    @livewire('ultimatetags::show-tags')
@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            console.log(1);
            $('#deleteTagModal').modal('hide');
            $('#editTagModal').modal('hide');
            $('#addTagModal').modal('hide');
        });
    </script>
@endsection
