@extends('layouts.app')

@section('content')

    <div>
        @livewire('show-filter')
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#addFilterModal').modal('hide');
            $('#editFilterModal').modal('hide');
            $('#deleteFilterModal').modal('hide');
        });
    </script>
@endsection
