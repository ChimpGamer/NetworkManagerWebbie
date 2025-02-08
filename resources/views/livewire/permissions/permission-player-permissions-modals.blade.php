<!-- Add Player Permission Modal -->
<div wire:ignore.self class="modal fade" id="addPlayerPermissionModal" tabindex="-1" aria-labelledby="addPlayerPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPlayerPermissionModalLabel">Add Player Permission</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createPlayerPermission'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Permission</label>
                        <input type="text" wire:model.live="permission" class="form-control">
                        @error('permission') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Server</label>
                        <input type="text" wire:model.live="server" class="form-control">
                        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">World</label>
                        <input type="text" wire:model.live="world" class="form-control">
                        @error('world') <span class="text-danger">{{ $message }}</span> @enderror
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


<!-- Update Player Permission Modal -->
<div wire:ignore.self class="modal fade" id="editPlayerPermissionModal" tabindex="-1" aria-labelledby="editPlayerPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlayerPermissionModalLabel">Edit Player Permission</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updatePlayerPermission'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Permission</label>
                        <input type="text" wire:model.live="permission" class="form-control">
                        @error('permission') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Server</label>
                        <input type="text" wire:model.live="server" class="form-control">
                        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">World</label>
                        <input type="text" wire:model.live="world" class="form-control">
                        @error('world') <span class="text-danger">{{ $message }}</span> @enderror
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

<!-- Delete Player Permission Modal -->
<div wire:ignore.self class="modal fade" id="deletePlayerPermissionModal" tabindex="-1" aria-labelledby="deletePlayerPermissionModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePlayerPermissionModalLabel">Delete Player Permission Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the permission `{{ $permission }}` from {{ $player->name }}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>
