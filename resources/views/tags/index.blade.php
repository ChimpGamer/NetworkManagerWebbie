@extends('layouts.app')

@section('content')

    <div>
        @livewire('show-tags')
    </div>

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
