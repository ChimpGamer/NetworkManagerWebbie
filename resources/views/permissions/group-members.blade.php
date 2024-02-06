@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-group-members', ['group' => $group])
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editGroupModal').modal('hide');
            $('#addGroupModal').modal('hide');
            $('#deleteGroupModal').modal('hide');
        });
    </script>
@endsection
