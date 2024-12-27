<div>
    @include('livewire.permissions.permission-group-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-success">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Permission Groups">
        <livewire:permissions.groups-table/>
    </x-card-table>
    @can('edit_permissions')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addGroupModal"
                    wire:click="addGroup">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Group
            </button>
        </div>
    @endcan
</div>
