@extends('layouts.app')

@section('content')

    <div>
        @livewire('servers.show-servers')
        <hr class="hr">
        <br>
        @livewire('servers.show-server-groups')
    </div>

@endsection

@section('script')
<script>
    window.addEventListener('close-modal', () => {
        $('#editServerModal').modal('hide');
        $('#addServerModal').modal('hide');
        $('#addServerGroupModal').modal('hide');
        $('#editServerGroupModal').modal('hide');
        $('#deleteServerGroupModal').modal('hide');
    });
</script>
@endsection

