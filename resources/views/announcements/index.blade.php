@extends('layouts.app')

@section('content')

    <div>
        <livewire:show-announcements>
    </div>

@endsection

@section('script')
<script>
    window.addEventListener('close-modal', () => {
        $('#editAnnouncementModal').modal('hide');
        $('#addAnnouncementModal').modal('hide');
    });
</script>
@endsection

