<div>
    @include('livewire.servers.servergroup-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <x-card-table title="Server Groups">
        <livewire:servers.server-groups-table/>
    </x-card-table>
    <div class="p-4">
        <button type="button" class="btn btn-primary"
                data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addServerGroupModal"
                wire:click="addServerGroup">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Group
        </button>
    </div>
</div>
