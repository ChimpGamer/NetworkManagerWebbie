@extends('layouts.app')

@section('content')

    <div>
        @livewire('languages.show-languages')
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#addLanguageModal').modal('hide');
            $('#deleteLanguageModal').modal('hide');
        });
    </script>
@endsection
