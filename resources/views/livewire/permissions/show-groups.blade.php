<div>
    @include('livewire.permissions.permission-group-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Permission Groups</h5>
            <label for="groupSearch" class="float-end mx-2">
                <input id="groupSearch" type="search" wire:model.live="search" class="form-control"
                       placeholder="Search..."/>
            </label>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table class="table text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Ladder</th>
                    <th>Rank</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($groups as $group)
                    <tr>
                        <td>{{ $group->id }}</td>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->ladder }}</td>
                        <td>{{ $group->rank }}</td>
                        <td>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal"
                                    data-mdb-target="#showPermissionGroupModal"
                                    wire:click="showGroup({{$group->id}})">
                                <i class="material-icons text-info">info</i>
                            </button>
                            @can('edit_permissions')
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-toggle="modal"
                                        data-mdb-target="#editGroupModal"
                                        wire:click="editGroup({{$group->id}})">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-toggle="modal"
                                        data-mdb-target="#deleteGroupModal"
                                        wire:click="deleteGroup({{ $group->id }})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $groups->links() }}
            </div>
        </div>
    </div>
    @can('edit_permissions')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#addGroupModal"
                    wire:click="addGroup">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Group
            </button>
        </div>
    @endcan
</div>
