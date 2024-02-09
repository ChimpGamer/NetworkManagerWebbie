@extends('layouts.app')

@section('content')

    <div>
        @livewire('permissions.show-player-permissions', ['player' => $player])
    </div>

@endsection

@section('script')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editPlayerPermissionModal').modal('hide');
            $('#addPlayerPermissionModal').modal('hide');
            $('#deletePlayerPermissionModal').modal('hide');
        });
    </script>
@endsection
