<div>
    @include('livewire.servers.server-modals')

    @if (session()->has('message'))
    <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header h5">
            Servers
            <label for="serverSearch" class="float-end mx-2">
                <input id="serverSearch" type="search" wire:model="search" class="form-control"
                    placeholder="Search..." />
            </label>
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
                    @foreach($servers as $server)
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
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal" data-mdb-target="#showServerModal"
                                wire:click="showServer({{$server->id}})">
                                <i class="material-icons text-info">info</i>
                            </button>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal" data-mdb-target="#editServerModal"
                                wire:click="editServer({{$server->id}})">
                                <i class="material-icons text-warning">edit</i>
                            </button>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal" data-mdb-target="#deleteServerModal"
                            wire:click="deleteServer({{ $server->id }})">
                                <i class="material-icons text-danger">delete</i>
                            </button>
                        </th>
                        {{--<th><button class="viewDetails" type="button" data-id="{{ $server->id  }}">View</button>
                        </th>--}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $servers->links() }}
        </div>
    </div>

</div>
