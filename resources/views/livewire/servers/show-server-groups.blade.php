<div>
    @include('livewire.servers.servergroup-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row mt-2 align-items-center text-center">
                <div class="col-md-12">
                    <h5 class="mb-0">
                        <strong>Server Groups</strong>
                    </h5>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <livewire:servers.server-groups-table/>
        </div>
    </div>
    <div class="p-4">
        <button type="button" class="btn btn-primary"
                data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addServerGroupModal"
                wire:click="addServerGroup">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Group
        </button>
    </div>
</div>
