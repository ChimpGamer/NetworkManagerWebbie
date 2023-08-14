<!-- Show Punishment Modal -->
<div wire:ignore.self class="modal fade" id="showPunishmentModal" tabindex="-1"
     aria-labelledby="showPunishmentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showPunishmentModalLabel">Show Punishment</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Type</strong>
                    <p>{{ $typeName }}</p>
                </div>
                <div class="mb-3">
                    <strong>Player</strong>
                    <p>{{ $playerName }}</p>
                </div>
                <div class="mb-3">
                    <strong>Punisher</strong>
                    <p>{{ $punisherName }}</p>
                </div>
                <div class="mb-3">
                    <strong>Server</strong>
                    <p>{{ $server }}</p>
                </div>
                <div class="mb-3">
                    <strong>Time</strong>
                    <p>{{ $timeFormatted }}</p>
                </div>
                @if($isTemporary)
                    <div class="mb-3">
                        <strong>End</strong>
                        <p>{{ $endFormatted }}</p>
                    </div>
                @endif
                <div class="mb-3">
                    <strong>Reason</strong>
                    <p>{!! $reason !!}</p>
                </div>
                <div class="mb-3">
                    <strong>Silent</strong>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="flexSwitchCheckCheckedDisabled" @checked(old('silent', $silent)) disabled/>
                        <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Silent</label>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Active</strong>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="flexSwitchCheckCheckedDisabled" @checked(old('active', $active)) disabled/>
                        <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Active</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" disabled>Unban</button>
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Punishment Modal -->
<div wire:ignore.self class="modal fade" id="addPunishmentModal" tabindex="-1"
     aria-labelledby="addPunishmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPunishmentModalLabel">Add Punishment</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent='createPunishment'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Type</label>
                        <select name="type" class="form-control" wire:model="typeId">
                            @foreach(\App\Models\PunishmentType::cases() as $punishmentType)
                                <option
                                    value="{{$punishmentType}}">{{ $punishmentType->name() }}</option>
                            @endforeach
                        </select>
                        @error('typeId') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Player</label>
                        <input type="text" wire:model="playerUUID" class="form-control">
                        @error('playerUUID') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Punisher</label>
                        <input type="text" wire:model="punisherUUID" class="form-control">
                        @error('punisherUUID') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Time</label>
                        <input type="datetime-local" wire:model="time" class="form-control">
                        @error('time') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if($isTemporary)
                        <div class="mb-3">
                            <label class="bold">End</label>
                            <input type="datetime-local" wire:model="end" class="form-control">
                            @error('end') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="bold">Reason</label>
                        <input type="text" wire:model="reason" class="form-control">
                        @error('reason') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if(!$isGlobal)
                        <div class="mb-3">
                            <label class="bold">Server</label>
                            <input type="text" wire:model="server" class="form-control">
                            @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="bold">Silent</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="silentSwitch"
                                       wire:model="silent" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="silentSwitch"><strong>On</strong></label>
                            </div>
                            @error('silent') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bold">Active</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="activeSwitch"
                                       wire:model="active" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="activeSwitch"><strong>On</strong></label>
                            </div>
                            @error('active') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">Close
                    </button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Punishment Modal -->
<div wire:ignore.self class="modal fade" id="editPunishmentModal" tabindex="-1"
     aria-labelledby="editPunishmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPunishmentModalLabel">Edit Punishment</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent='updatePunishment'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Type</label>
                        <select name="type" class="form-control" wire:model="typeId">
                            @foreach(\App\Models\PunishmentType::cases() as $punishmentType)
                                <option
                                    value="{{$punishmentType}}">{{ $punishmentType->name() }}</option>
                            @endforeach
                        </select>
                        @error('typeId') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Player</label>
                        <input type="text" wire:model="playerUUID" class="form-control">
                        @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Punisher</label>
                        <input type="text" wire:model="punisherUUID" class="form-control">
                        @error('sound') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Time</label>
                        <input type="datetime-local" wire:model="time" class="form-control">
                        @error('time') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if($isTemporary)
                        <div class="mb-3">
                            <label class="bold">End</label>
                            <input type="datetime-local" wire:model="end" class="form-control">
                            @error('end') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="bold">Reason</label>
                        <input type="text" wire:model="reason" class="form-control">
                        @error('reason') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if(!$isGlobal)
                        <div class="mb-3">
                            <label class="bold">Server</label>
                            <input type="text" wire:model="server" class="form-control">
                            @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="bold">Silent</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="silentSwitch"
                                       wire:model="silent" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="silentSwitch"><strong>On</strong></label>
                            </div>
                            @error('silent') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bold">Active</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="activeSwitch"
                                       wire:model="active" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="activeSwitch"><strong>On</strong></label>
                            </div>
                            @error('active') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">Close
                    </button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Punishment Modal -->
<div wire:ignore.self class="modal fade" id="deletePunishmentModal" tabindex="-1"
     aria-labelledby="deletePunishmentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePunishmentModalLabel">Delete Punishment Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete punishment {{ $deleteId }}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close
                </button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal"
                        data-mdb-dismiss="modal">Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>
