<div>
    @include('livewire.servers.server-modals')

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
                        <strong>Servers</strong>
                    </h5>
                </div>
                <div class="col-md-auto ms-auto" wire:ignore>
                    <div class="form-outline w-auto d-inline-block" data-mdb-input-init>
                        <input type="search" id="serverSearch" class="form-control form-control-sm" wire:model.live="search"/>
                        <label class="form-label" for="serverSearch" style="font-family: Roboto, 'FontAwesome'">Search...</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="serversTable" class="table text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>ServerName</th>
                    <th>DisplayName</th>
                    <th>IP</th>
                    <th>Port</th>
                    <th>Online</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse($servers as $server)
                    <tr>
                        <td>{{ $server->id }}</td>
                        <td>{{ $server->servername }}</td>
                        <td>{!! $server->displayname !!}</td>
                        <td>{{ $server->ip }}</td>
                        <td>{{ $server->port }}</td>
                        <td>
                            <span @class(['label', 'label-success' => $server->online, 'label-danger' => ! $server->online])>
                                @if ($server->online)
                                    Online
                                @else
                                    Offline
                                @endif
                            </span>
                        </td>
                        <th>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#showServerModal"
                                    wire:click="showServer({{$server->id}})">
                                <i class="material-icons text-info">info</i>
                            </button>
                            @can('edit_servers')
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#editServerModal"
                                        wire:click="editServer({{$server->id}})">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#deleteServerModal"
                                        wire:click="deleteServer({{ $server->id }})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            @endcan
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $servers->links() }}
        </div>
    </div>
    @can('edit_servers')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addServerModal"
                    wire:click="addServer">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Server
            </button>
        </div>
    @endcan
</div>
