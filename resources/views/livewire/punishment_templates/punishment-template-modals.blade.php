<!-- Show Punishment Modal -->
<div wire:ignore.self class="modal fade" id="showPunishmentTemplateModal" tabindex="-1"
     aria-labelledby="showPunishmentTemplateModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showPunishmentTemplateModalLabel">Show Punishment Template</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Name</strong>
                    <p>{{ $name }}</p>
                </div>
                <div class="mb-3">
                    <strong>Type</strong>
                    <p>{{ $type }}</p>
                </div>
                <div class="mb-3">
                    <strong>Duration (Seconds)</strong>
                    <p>{{ $duration }}</p>
                </div>
                <div class="mb-3">
                    <strong>Server</strong>
                    <p>{{ $server }}</p>
                </div>
                <div class="mb-3">
                    <strong>Reason</strong>
                    <p>{!!  $reason  !!}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Server Modal -->
<div wire:ignore.self class="modal fade" id="addTemplateModal" tabindex="-1" aria-labelledby="addTemplateModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGroupModalLabel">Add Punishment Template</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createTemplate'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Name</label>
                        <input type="text" wire:model="name" class="form-control">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Type</label>
                        <select name="type" class="form-control" wire:model.change="typeId">
                            @foreach($this->punishmentTypeCases as $punishmentType)
                                <option value="{{$punishmentType}}">{{ $punishmentType->name() }}</option>
                            @endforeach
                        </select>
                        @error('typeId') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if($isTemporary)
                        <div class="mb-3">
                            <label class="bold">Duration (Seconds)</label>
                            <input type="number" wire:model="duration" class="form-control" placeholder="Duration in seconds...">
                            @error('duration') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    @if(!$isGlobal)
                        <div class="mb-3">
                            <label class="bold">Server</label>
                            <input type="text" wire:model="server" class="form-control">
                            @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="bold">Reason</label>
                        <input type="text" wire:model="reason" class="form-control">
                        @error('reason') <span class="text-danger">{{ $message }}</span> @enderror
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
<div wire:ignore.self class="modal fade" id="editPunishmentTemplateModal" tabindex="-1"
     aria-labelledby="editPunishmentTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPunishmentTemplateModalLabel">Edit Punishment Template</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updateTemplate'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Name</label>
                        <input type="text" wire:model="name" class="form-control">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Type</label>
                        <select name="type" class="form-control" wire:model.change="typeId">
                            @foreach($this->punishmentTypeCases as $punishmentType)
                                <option
                                    value="{{$punishmentType}}">{{ $punishmentType->name() }}</option>
                            @endforeach
                        </select>
                        @error('typeId') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if($isTemporary)
                        <div class="mb-3">
                            <label class="bold">Duration (Seconds)</label>
                            <input type="number" wire:model="duration" class="form-control" placeholder="Duration in seconds...">
                            @error('duration') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    @if(!$isGlobal)
                        <div class="mb-3">
                            <label class="bold">Server</label>
                            <input type="text" wire:model="server" class="form-control">
                            @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="bold">Reason</label>
                        <input type="text" wire:model="reason" class="form-control">
                        @error('reason') <span class="text-danger">{{ $message }}</span> @enderror
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

<!-- Delete Punishment Template Modal -->
<div wire:ignore.self class="modal fade" id="deletePunishmentTemplateModal" tabindex="-1"
     aria-labelledby="deletePunishmentTemplateModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePunishmentTemplateModalLabel">Delete Punishment Template Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete punishment template {{ $deleteId }} ({{ $deleteName }})?</p>
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
