<div>
    @include('livewire.accounts.account-group-modals')

    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Account Groups">
        <livewire:accounts.account-groups-table/>
    </x-card-table>
    @can('manage_groups_and_accounts')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#addAccountGroupModal"
                    wire:click="addAccountGroup">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Account Group
            </button>
        </div>
    @endcan
</div>
