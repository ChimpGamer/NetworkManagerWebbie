<div>
    @include('livewire.permissions.permission-player-permissions-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Permissions of {{ $player->name }}</h5>
            <label for="playerPermissionSearch" class="float-end mx-2">
                <input id="playerPermissionSearch" type="search" wire:model="search" class="form-control"
                       placeholder="Search..."/>
            </label>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table class="table text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Permission</th>
                    <th>Server</th>
                    <th>World</th>
                    <th>Expires</th>
                    @can('edit_permissions')
                        <th>Actions</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach($permissions as $permission)
                    @php
                        $server = $permission->server;
                        $world = $permission->world;
                        $expires = $permission->expires;
                        if (empty($server)) {
                            $server = "ALL";
                        }
                        if (empty($world)) {
                            $world = "ALL";
                        }
                        if (empty($expires)) {
                            $expires = "Never";
                        }
                    @endphp
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td>{{ $permission->permission }}</td>
                        <td>{{ $server }}</td>
                        <td>{{ $world }}</td>
                        <td>{{ $expires }}</td>
                        @can('edit_permissions')
                            <td>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-toggle="modal"
                                        data-mdb-target="#editPlayerPermissionModal"
                                        wire:click="editPlayerPermission({{$permission->id}})">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-toggle="modal"
                                        data-mdb-target="#deletePlayerPermissionModal"
                                        wire:click="deletePlayerPermission({{ $permission->id }})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $permissions->links() }}
            </div>
        </div>
    </div>
    @can('edit_permissions')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-toggle="modal"
                    data-mdb-target="#addPlayerPermissionModal"
                    wire:click="addPlayerPermission">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Permission
            </button>
        </div>
    @endcan
</div>
