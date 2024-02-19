<!-- Add Group Suffix Modal -->
<div wire:ignore.self class="modal fade" id="addGroupSuffixModal" tabindex="-1" aria-labelledby="addGroupSuffixModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGroupSuffixModalLabel">Add Group Suffix</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createGroupSuffix'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Suffix</label>
                        <input type="text" wire:model.live="suffix" class="form-control">
                        @error('suffix') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Server</label>
                        <input type="text" wire:model.live="server" class="form-control">
                        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
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


<!-- Update Group Suffix Modal -->
<div wire:ignore.self class="modal fade" id="editGroupSuffixModal" tabindex="-1" aria-labelledby="editGroupSuffixModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGroupSuffixModalLabel">Edit Group Suffix</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updateGroupSuffix'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Suffix</label>
                        <input type="text" wire:model.live="suffix" class="form-control">
                        @error('suffix') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Server</label>
                        <input type="text" wire:model.live="server" class="form-control">
                        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
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

<!-- Delete Group Suffix Modal -->
<div wire:ignore.self class="modal fade" id="deleteGroupSuffixModal" tabindex="-1" aria-labelledby="deleteGroupSuffixModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteGroupSuffixModalLabel">Delete Group Suffix Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the suffix `{{ $suffix }}` from {{ $group->name }}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>
