<div>
    @include('livewire.accounts.account-group-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="{{ __('accounts.account-groups.title') }}">
        <livewire:accounts.account-groups-table/>
    </x-card-table>
    @can('manage_groups_and_accounts')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#addAccountGroupModal"
                    wire:click="addAccountGroup">
                <i style="font-size: 18px !important;" class="material-icons">add</i> @lang('accounts.account-groups.buttons.add')
            </button>
        </div>
    @endcan
</div>
