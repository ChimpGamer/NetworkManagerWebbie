<div>
    @include('livewire.punishments.punishment-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Punishments">
        <livewire:punishments.punishments-table/>
    </x-card-table>
    @can('edit_punishments')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addPunishmentModal"
                    wire:click="addPunishment">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Punishment
            </button>
        </div>
    @endcan
</div>
