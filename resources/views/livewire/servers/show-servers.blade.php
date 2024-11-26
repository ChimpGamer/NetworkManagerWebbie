<div>
    @include('livewire.servers.server-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row mt-2 align-items-center text-center">
                <div class="col-md-12">
                    <h5 class="mb-0">
                        <strong>Servers</strong>
                    </h5>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <livewire:servers.servers-table/>
        </div>
    </div>
    @can('edit_servers')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addServerModal"
                    wire:click="addServer">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Server
            </button>
        </div>
    @endcan
</div>
