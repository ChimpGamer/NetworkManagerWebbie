<div>
    @include('livewire.punishments.punishment-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session()->has('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header h5">
            Punishments
            <label for="punishmentSearch" class="float-end mx-2">
                <input id="punishmentSearch" type="search" wire:model.live="search" class="form-control"
                       placeholder="Search..."/>
            </label>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="punishmentsTable" class="table text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Player</th>
                    <th>Punisher</th>
                    <th>Reason</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($punishments as $punishment)
                    <tr>
                        <td>@if ($punishment->active)
                                <i class="fas fa-check-circle fa-lg"></i>
                            @else
                                <i class="fas fa-exclamation-circle fa-lg"></i>
                            @endif {{ $punishment->id }}</td>
                        <td>{{ $punishment->type->name() }}</td>
                        <td><a wire:navigate href="/players/{{$punishment->uuid}}">{{ $punishment->getPlayerName() }}</a></td>
                        <td>{{ $punishment->getPunisherName() }}</td>
                        <td>{!! $punishment->reason !!}</td>
                        <td>{{ $punishment->getTimeFormatted() }}</td>
                        <th>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#showPunishmentModal"
                                    wire:click="showPunishment({{$punishment->id}})">
                                <i class="material-icons text-info">info</i>
                            </button>
                            @can('edit_punishments')
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#editPunishmentModal"
                                        wire:click="editPunishment({{$punishment->id}})">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#deletePunishmentModal"
                                        wire:click="deletePunishment({{ $punishment->id }})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            @endcan
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $punishments->links() }}
        </div>
    </div>
    @can('edit_punishments')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addPunishmentModal"
                    wire:click="addPunishment">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Punishment
            </button>
        </div>
    @endcan
</div>
