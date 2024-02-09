@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-player-groups', ['player' => $player])
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editPlayerGroupModal').modal('hide');
            $('#addPlayerGroupModal').modal('hide');
            $('#deletePlayerGroupModal').modal('hide');
        });
    </script>
@endsection
