<div>
    @include('livewire.servers.servergroup-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0 text-center">
                <strong>Server Groups</strong>
            </h5>

            <div class="float-end d-inline" wire:ignore>
                <div class="form-outline" data-mdb-input-init>
                    <input type="search" id="serverGroupSearch" class="form-control" wire:model.live="search"/>
                    <label class="form-label" for="serverGroupSearch"
                           style="font-family: Roboto, 'FontAwesome'">Search...</label>
                </div>
            </div>
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
                @forelse($servergroups as $serverGroup)
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
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse
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
