<!-- Add Player Group Modal -->
<div wire:ignore.self class="modal fade" id="addGroupMemberModal" tabindex="-1" aria-labelledby="addGroupMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGroupMemberModalLabel">Add Group Member</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createGroupMember'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Player UUID</label>
                        <input type="text" wire:model.blur="memberUUID" class="form-control">
                        @error('memberUUID') <span class="text-danger">{{ $message }}</span> @enderror
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

<!-- Delete Group Member Modal -->
<div wire:ignore.self class="modal fade" id="deleteGroupMemberModal" tabindex="-1" aria-labelledby="deleteGroupMemberModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteGroupMemberModalLabel">Delete Group Member Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete {{ $memberName }} as a member from {{ $groupName }}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>
