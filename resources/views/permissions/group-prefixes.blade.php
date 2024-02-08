@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-group-prefixes', ['group' => $group])
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editGroupPrefixModal').modal('hide');
            $('#addGroupPrefixModal').modal('hide');
            $('#deleteGroupPrefixModal').modal('hide');
        });
    </script>
@endsection
