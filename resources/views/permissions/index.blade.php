@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-groups')
        <hr class="hr">
        <br>
        @livewire('permissions.show-players')
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editGroupModal').modal('hide');
            $('#addGroupModal').modal('hide');
            $('#deleteGroupModal').modal('hide');
            $('#editPermissionPlayerModal').modal('hide');
        });
    </script>
@endsection
