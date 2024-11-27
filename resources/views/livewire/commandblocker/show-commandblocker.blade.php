<div>
    @include('livewire.commandblocker.commandblocker-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Command Blockers">
        <livewire:command-blockers.command-blockers-table/>
    </x-card-table>
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
