@extends('layouts.app')

@section('content')

    <div>
        <livewire:show-punishment-templates>
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#addTemplateModal').modal('hide');
            $('#editTemplateModal').modal('hide');
        });
    </script>
@endsection
