<div>
    @include('livewire.permissions.permission-player-permissions-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Permissions of {{ $player->name }}">
        <livewire:permissions.permission-player-permissions-table uuid="{{ $player->uuid }}" />
    </x-card-table>
    @can('edit_permissions')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#addPlayerPermissionModal"
                    wire:click="addPlayerPermission">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Permission
            </button>
        </div>
    @endcan
</div>
