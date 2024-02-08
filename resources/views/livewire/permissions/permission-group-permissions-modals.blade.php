<!-- Add Group Permission Modal -->
<div wire:ignore.self class="modal fade" id="addGroupPermissionModal" tabindex="-1" aria-labelledby="addGroupPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGroupPermissionModalLabel">Add Group Permission</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent='createGroupPermission'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Permission</label>
                        <input type="text" wire:model="permission" class="form-control">
                        @error('permission') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Server</label>
                        <input type="text" wire:model="server" class="form-control">
                        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">World</label>
                        <input type="text" wire:model="world" class="form-control">
                        @error('world') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Expires</label>
                        <input type="datetime-local" wire:model="expires" class="form-control">
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


<!-- Update GroupPermission Modal -->
<div wire:ignore.self class="modal fade" id="editGroupPermissionModal" tabindex="-1" aria-labelledby="editGroupPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGroupPermissionModalLabel">Edit Group Permission</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent='updateGroupPermission'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Permission</label>
                        <input type="text" wire:model="permission" class="form-control">
                        @error('permission') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Server</label>
                        <input type="text" wire:model="server" class="form-control">
                        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">World</label>
                        <input type="text" wire:model="world" class="form-control">
                        @error('world') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Expires</label>
                        <input type="datetime-local" wire:model="expires" class="form-control">
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

<!-- Delete Group Permission Modal -->
<div wire:ignore.self class="modal fade" id="deleteGroupPermissionModal" tabindex="-1" aria-labelledby="deleteGroupPermissionModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteGroupPermissionModalLabel">Delete Group Permission Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the permission `{{ $permission }}` from {{ $group->name }}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>
