<div>
    @include('livewire.servers.server-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <x-card-table title="Servers">
        <livewire:servers.servers-table/>
    </x-card-table>
    @can('edit_servers')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addServerModal"
                    wire:click="addServer">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Server
            </button>
        </div>
    @endcan
</div>
