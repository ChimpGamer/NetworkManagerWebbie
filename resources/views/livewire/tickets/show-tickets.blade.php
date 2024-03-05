<div>

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header h5">
            Tickets
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="serversTable" class="table text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Creator</th>
                    <th>Title</th>
                    <th>Assigned</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach($tickets as $ticket)
                    <tr>
                        <td>@if ($ticket->active)
                                <i class="fas fa-check-circle fa-lg text-success"></i>
                            @else
                                <i class="fas fa-exclamation-circle fa-lg text-danger"></i>
                            @endif {{ $ticket->id }}</td>
                        <td>{{ $ticket->getCreatorName() }}</td>
                        <td>{{ $ticket->title }}</td>
                        <td>@if(empty($ticket->assigned_to))
                                Unassigned
                            @else
                                {{ $ticket->assigned_to }}
                            @endif</td>
                        <td>{{ $ticket->getCreationFormatted() }}</td>
                        <td>{{ $ticket->getLastUpdateFormatted() }}</td>
                        <th>
                            <a type="button" style="background: transparent; border: none;" href="/tickets/{{$ticket->id}}">
                                <i class="material-icons text-info">info</i>
                            </a>
                            {{--@can('edit_servers')
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
                            @endcan--}}
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
    {{--@can('edit_servers')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addServerModal"
                    wire:click="addServer">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Server
            </button>
        </div>
    @endcan--}}
</div>
