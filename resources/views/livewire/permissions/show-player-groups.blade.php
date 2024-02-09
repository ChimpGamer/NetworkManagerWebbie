<div>
    @include('livewire.permissions.permission-player-groups-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if(session()->has('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Groups of {{ $player->name }}</h5>
            <label for="playerGroupSearch" class="float-end mx-2">
                <input id="playerGroupSearch" type="search" wire:model="search" class="form-control"
                       placeholder="Search..."/>
            </label>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table class="table text-center">
                <thead>
                <tr>
                    <th>Group</th>
                    <th>Server</th>
                    <th>Expires</th>
                    @can('edit_permissions')
                        <th>Actions</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach($playerGroups as $playerGroup)
                    @php
                        $server = $playerGroup->server;
                        $expires = $playerGroup->expires;
                        if (empty($server)) {
                            $server = "ALL";
                        }
                        if (empty($expires)) {
                            $expires = "Never";
                        }
                    @endphp
                    <tr>
                        <td>{{ $playerGroup->group->name }}</td>
                        <td>{{ $server }}</td>
                        <td>{{ $expires }}</td>
                        @can('edit_permissions')
                            <td>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-toggle="modal"
                                        data-mdb-target="#editPlayerGroupModal"
                                        wire:click="editPlayerGroup({{ $playerGroup->id }}, {{ $playerGroup->group->id }})">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-toggle="modal"
                                        data-mdb-target="#deletePlayerGroupModal"
                                        wire:click="deletePlayerGroup({{ $playerGroup->id }}, {{ $playerGroup->group->id }})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $playerGroups->links() }}
            </div>
        </div>
    </div>
    @can('edit_permissions')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-toggle="modal"
                    data-mdb-target="#addPlayerGroupModal"
                    wire:click="addPlayerGroup">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Group
            </button>
        </div>
    @endcan
</div>
