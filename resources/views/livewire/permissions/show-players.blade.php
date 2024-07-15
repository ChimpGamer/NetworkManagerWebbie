<div>
    @include('livewire.permissions.permission-player-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row mt-2 justify-content-between text-center">
                <div class="col-md-auto me-auto">
                    <label>Show
                        <select class="form-select form-select-sm" style="display: inherit; width: auto" wire:model.live="per_page">
                            <option value=10>10</option>
                            <option value=25>25</option>
                            <option value=50>50</option>
                            <option value=100>100</option>
                        </select>
                        entries
                    </label>
                </div>
                <div class="col-md-auto">
                    <h5 class="mb-0 text-center">
                        <strong>Permisson Players</strong>
                    </h5>
                </div>
                <div class="col-md-auto ms-auto" wire:ignore>
                    <div class="form-outline w-auto d-inline-block" data-mdb-input-init>
                        <input type="search" id="permissionPlayerSearch" class="form-control form-control-sm" wire:model.live="search"/>
                        <label class="form-label" for="permissionPlayerSearch" style="font-family: Roboto, 'FontAwesome'">Search...</label>
                    </div>
                </div>
            </div>
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
                @forelse($players as $player)
                    <tr>
                        <td>{{ $player->uuid }}</td>
                        <td>{{ $player->name }}</td>
                        <td>{{ $player->prefix }}</td>
                        <td>{{ $player->suffix }}</td>
                        <td>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#showPermissionPlayerModal"
                                    wire:click="showPlayer('{{$player->uuid}}')">
                                <i class="material-icons text-info">info</i>
                            </button>
                            @can('edit_permissions')
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init
                                        data-mdb-target="#editPermissionPlayerModal"
                                        wire:click="editPlayer('{{$player->uuid}}')">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $players->links() }}
        </div>
    </div>
</div>
