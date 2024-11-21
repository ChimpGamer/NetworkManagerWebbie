<div>
    @include('livewire.commandblocker.commandblocker-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row mt-2 align-items-center text-center">
                <div class="col-md-12">
                    <h5 class="mb-0">
                        <strong>Command Blockers</strong>
                    </h5>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <livewire:commandblockers.commandblockers-table/>
        </div>
    </div>
    @can('edit_commandblocker')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#addCommandBlockerModal"
                    wire:click="addCommandBlocker">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add CommandBlocker
            </button>
        </div>
    @endcan
</div>
