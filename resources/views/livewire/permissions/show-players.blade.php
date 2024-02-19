<div>
    @include('livewire.permissions.permission-player-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Permission Players</h5>
            <label for="playerSearch" class="float-end mx-2">
                <input id="playerSearch" type="search" wire:model.live="search" class="form-control"
                       placeholder="Search..."/>
            </label>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table class="table text-center">
                <thead>
                <tr>
                    <th>UUID</th>
                    <th>Name</th>
                    <th>Prefix</th>
                    <th>Suffix</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($players as $player)
                    <tr>
                        <td>{{ $player->uuid }}</td>
                        <td>{{ $player->name }}</td>
                        <td>{{ $player->prefix }}</td>
                        <td>{{ $player->suffix }}</td>
                        <td>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal"
                                    data-mdb-target="#showPermissionPlayerModal"
                                    wire:click="showPlayer('{{$player->uuid}}')">
                                <i class="material-icons text-info">info</i>
                            </button>
                            @can('edit_permissions')
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-toggle="modal"
                                        data-mdb-target="#editPermissionPlayerModal"
                                        wire:click="editPlayer('{{$player->uuid}}')">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $players->links() }}
            </div>
        </div>
    </div>
</div>
