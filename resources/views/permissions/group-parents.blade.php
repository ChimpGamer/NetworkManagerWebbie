@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-group-parents', ['group' => $group])
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editGroupParentModal').modal('hide');
            $('#addGroupParentModal').modal('hide');
            $('#deleteGroupParentModal').modal('hide');
        });
    </script>
@endsection
