<div>
    @include('livewire.permissions.permission-player-groups-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if(session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Groups of {{ $player->name }}">
        <livewire:permissions.permission-player-groups-table uuid="{{ $player->uuid }}" />
    </x-card-table>
    @can('edit_permissions')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#addPlayerGroupModal"
                    wire:click="addPlayerGroup">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Group
            </button>
        </div>
    @endcan
</div>
