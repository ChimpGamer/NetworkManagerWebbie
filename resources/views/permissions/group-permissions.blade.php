@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-group-permissions', ['group' => $group])
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editGroupPermissionModal').modal('hide');
            $('#addGroupPermissionModal').modal('hide');
            $('#deleteGroupPermissionModal').modal('hide');
        });
    </script>
@endsection
