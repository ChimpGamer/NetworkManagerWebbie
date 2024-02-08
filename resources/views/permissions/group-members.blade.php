@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-group-members', ['group' => $group])
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editGroupMemberModal').modal('hide');
            $('#addGroupMemberModal').modal('hide');
            $('#deleteGroupMemberModal').modal('hide');
        });
    </script>
@endsection
