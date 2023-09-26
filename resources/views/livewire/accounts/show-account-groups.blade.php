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
                @foreach($accountGroups as $accountGroup)
                    <tr>
                        <td>{{ $accountGroup->name }}</td>
                        <td>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal"
                                    data-mdb-target="#editAccountGroupModal"
                                {{--wire:click="editPunishment({{$punishment->id}})"--}}>
                                <i class="material-icons text-warning">edit</i>
                            </button>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal"
                                    data-mdb-target="#deleteAccountGroupModal"
                                wire:click="deleteAccountGroup({{ $accountGroup->id }})">
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
        <button type="button" class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#addAccountGroupModal"
                wire:click="addAccountGroup">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Account Group
        </button>
    </div>
</div>
