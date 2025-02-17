<div>
    @include('livewire.accounts.account-modals')

    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="{{ __('accounts.accounts.title') }}">
        <livewire:accounts.accounts-table/>
    </x-card-table>
    @can('manage_groups_and_accounts')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#addAccountModal"
                    wire:click="addAccount">
                <i style="font-size: 18px !important;" class="material-icons">add</i> @lang('accounts.accounts.buttons.add')
            </button>
        </div>
    @endcan
</div>
