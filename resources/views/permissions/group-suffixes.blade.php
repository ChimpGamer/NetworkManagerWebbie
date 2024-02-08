@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-group-suffixes', ['group' => $group])
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editGroupSuffixModal').modal('hide');
            $('#addGroupSuffixModal').modal('hide');
            $('#deleteGroupSuffixModal').modal('hide');
        });
    </script>
@endsection
