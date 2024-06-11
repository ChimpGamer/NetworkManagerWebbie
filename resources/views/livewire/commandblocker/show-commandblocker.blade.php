<div>
    @include('livewire.commandblocker.commandblocker-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session()->has('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0 text-center">
                <strong>Command Blocker</strong>
            </h5>

            <div class="float-end d-inline" wire:ignore>
                <div class="form-outline" data-mdb-input-init>
                    <input type="search" id="commandBlockerSearch" class="form-control" wire:model.live="search"/>
                    <label class="form-label" for="commandBlockerSearch" style="font-family: Roboto, 'FontAwesome'">Search...</label>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Command</th>
                    <th>Server</th>
                    <th>Bypass Permission</th>
                    @can('edit_commandblocker')
                        <th>Actions</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @forelse($blockedcommands as $blockedCommand)
                    <tr>
                        <td>@if ($blockedCommand->enabled)
                                <i class="fas fa-check-circle fa-lg text-success"></i>
                            @else
                                <i class="fas fa-exclamation-circle fa-lg text-danger"></i>
                            @endif{{ $blockedCommand->id }}</td>
                        <td>{{ $blockedCommand->name }}</td>
                        <td>{{ $blockedCommand->command }}</td>
                        <td>{{ $blockedCommand->server }}</td>
                        <td>
                            @if ($blockedCommand->bypasspermission)
                                <i class="fas fa-check-circle fa-lg"></i>
                            @else
                                <i class="fas fa-circle-xmark fa-lg"></i>
                            @endif
                        </td>
                        <td>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#showCommandBlockerModal"
                                    wire:click="showCommandBlock({{ $blockedCommand->id }})">
                                <i class="material-icons text-info">info</i>
                            </button>
                            @can('edit_commandblocker')
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init
                                        data-mdb-target="#editCommandBlockerModal"
                                        wire:click="editCommandBlocker({{$blockedCommand->id}})">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init
                                        data-mdb-target="#deleteCommandBlockerModal"
                                        wire:click="deleteCommandBlocker({{$blockedCommand->id}})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $blockedcommands->links() }}
        </div>
    </div>
    @can('edit_commandblocker')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#addCommandBlockerModal"
                    wire:click="addCommandBlocker">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add CommandBlocker
            </button>
        </div>
    @endcan
</div>
