@extends('layouts.app')

@section('content')

    <div>
        <livewire:show-servers>
    </div>

@endsection

@section('script')
<script>
    window.addEventListener('close-modal', () => {
        $('#editServerModal').modal('hide');
    });
    window.addEventListener('close-modal', () => {
        $('#addServerModal').modal('hide');
    });
</script>
@endsection

