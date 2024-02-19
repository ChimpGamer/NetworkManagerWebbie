<!-- Add Player Group Modal -->
<div wire:ignore.self class="modal fade" id="addPlayerGroupModal" tabindex="-1" aria-labelledby="addPlayerGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPlayerGroupModalLabel">Add Player Group</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createPlayerGroup'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Group</label>
                        <input type="text" wire:model.blur="groupName" class="form-control" list="group-list">
                        <datalist id="group-list">
                            @foreach($groups as $group)
                                <option
                                    wire:key="{{ $group['id'] }}"
                                    value="{{ $group['name'] }}"
                                ></option>
                            @endforeach
                        </datalist>
                        @error('groupName') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Server</label>
                        <input type="text" wire:model.live="server" class="form-control">
                        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Expires</label>
                        <input type="datetime-local" wire:model.live="expires" class="form-control">
                        @error('expires') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Update Player Group Modal -->
<div wire:ignore.self class="modal fade" id="editPlayerGroupModal" tabindex="-1" aria-labelledby="editPlayerGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlayerGroupModalLabel">Edit Player Group</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updatePlayerGroup'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Group</label>
                        <input type="text" wire:model.live="groupName" class="form-control">
                        @error('groupName') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Server</label>
                        <input type="text" wire:model.live="server" class="form-control">
                        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Expires</label>
                        <input type="datetime-local" wire:model.live="expires" class="form-control">
                        @error('expires') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Player Group Modal -->
<div wire:ignore.self class="modal fade" id="deletePlayerGroupModal" tabindex="-1" aria-labelledby="deletePlayerGroupModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePlayerGroupModalLabel">Delete Player Group Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the group `{{ $groupName }}` from {{ $player->name }}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>
