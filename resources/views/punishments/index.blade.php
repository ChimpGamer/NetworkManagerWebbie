@extends('layouts.app')

@section('content')

    <div>
        <livewire:show-punishments>
    </div>

@endsection

@section('script')
<script>
    window.addEventListener('close-modal', () => {
        $('#editPunishmentModal').modal('hide');
    });
</script>
@endsection

