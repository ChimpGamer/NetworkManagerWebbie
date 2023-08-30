@extends('layouts.app')

@section('content')

    <div>
        @livewire('show-command-blocker')
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#addCommandBlockerModal').modal('hide');
            $('#editCommandBlockerModal').modal('hide');
            $('#deleteCommandBlockerModal').modal('hide');
        });
    </script>
@endsection
