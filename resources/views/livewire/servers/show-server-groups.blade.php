<div>
    @include('livewire.servers.servergroup-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header h5">
            Server Groups
            <label for="serverGroupSearch" class="float-end mx-2">
                <input id="serverGroupSearch" type="search" wire:model.live="search" class="form-control"
                       placeholder="Search..."/>
            </label>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="serverGroupsTable" class="table text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>ServerName</th>
                    <th>Balance Method</th>
                    <td>Actions</td>
                </tr>
                </thead>

                <tbody>
                @foreach($servergroups as $serverGroup)
                    <tr>
                        <td>{{ $serverGroup->id }}</td>
                        <td>{{ $serverGroup->groupname }}</td>
                        <td>{{ $serverGroup->balancemethodtype }}</td>
                        <th>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#showServerGroupModal"
                                    wire:click="showServerGroup({{$serverGroup->id}})">
                                <i class="material-icons text-info">info</i>
                            </button>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#editServerGroupModal"
                                wire:click="editServerGroup({{$serverGroup->id}})">
                                <i class="material-icons text-warning">edit</i>
                            </button>
                            @can('edit_servers')
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#deleteServerGroupModal"
                                        wire:click="deleteServerGroup({{ $serverGroup->id }})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            @endcan
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $servergroups->links() }}
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
