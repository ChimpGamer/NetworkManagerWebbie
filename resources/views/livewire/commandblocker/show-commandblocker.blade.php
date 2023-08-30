<div>
    @include('livewire.commandblocker.commandblocker-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session()->has('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header text-center py-3">
            <h5 class="mb-0 text-center">
                <strong>Command Blocker</strong>
            </h5>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Command</th>
                    <th>Server</th>
                    <th>Custom Message</th>
                    <th>Bypass Permission</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($blockedcommands as $blockedCommand)
                    <tr>
                        <td>{{ $blockedCommand->id }}</td>
                        <td>{{ $blockedCommand->command }}</td>
                        <td>{{ $blockedCommand->server }}</td>
                        <td>{!! $blockedCommand->customMessage !!}</td>
                        <td>
                            @if ($blockedCommand->bypasspermission)
                                <i class="fas fa-check-circle fa-lg"></i>
                            @else
                                <i class="fas fa-circle-xmark fa-lg"></i>
                            @endif
                        </td>
                        <td>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal"
                                    data-mdb-target="#editCommandBlockerModal"
                                    wire:click="editCommandBlocker({{$blockedCommand->id}})">
                                <i class="material-icons text-warning">edit</i>
                            </button>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal"
                                    data-mdb-target="#deleteCommandBlockerModal"
                                    wire:click="deleteCommandBlocker({{$blockedCommand->id}})">
                                <i class="material-icons text-danger">delete</i>
                            </button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $blockedcommands->links() }}
            </div>
        </div>
    </div>
    <div class="p-4">
        <button type="button" class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#addCommandBlockerModal"
                wire:click="addCommandBlocker">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add CommandBlocker
        </button>
    </div>
</div>
