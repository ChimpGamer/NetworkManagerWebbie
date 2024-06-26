<div>
    @include('livewire.accounts.account-group-modals')

    <div class="card">
        <div class="card-header h5">
            Account Groups
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="accountGroupsTable" class="table text-center">
                <thead>
                <tr>
                    <th>Group</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($accountGroups as $accountGroup)
                    <tr>
                        <td>{{ $accountGroup->name }}</td>
                        <td>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#editAccountGroupModal"
                                    wire:click="editAccountGroup({{ $accountGroup->id }})">
                                <i class="material-icons text-warning">edit</i>
                            </button>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#deleteAccountGroupModal"
                                    wire:click="deleteAccountGroup({{ $accountGroup->id }})">
                                <i class="material-icons text-danger">delete</i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="p-4">
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addAccountGroupModal"
                wire:click="addAccountGroup">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Account Group
        </button>
    </div>
</div>
