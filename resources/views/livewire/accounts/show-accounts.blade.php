<div>
    @include('livewire.accounts.account-modals')

    <div class="card">
        <div class="card-header h5">
            Accounts
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="accountsTable" class="table text-center">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Group</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($accounts as $account)
                    <tr>
                        <td>{{ $account->username }}</td>
                        <td>{{ $account->usergroup }}</td>
                        <td>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#editAccountModal"
                                wire:click="editAccount({{$account->id}})">
                                <i class="material-icons text-warning">edit</i>
                            </button>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#deleteAccountModal"
                                wire:click="deleteAccount({{ $account->id }})">
                                <i class="material-icons text-danger">delete</i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="p-4">
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addAccountModal"
                wire:click="addAccount">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Account
        </button>
    </div>
</div>
