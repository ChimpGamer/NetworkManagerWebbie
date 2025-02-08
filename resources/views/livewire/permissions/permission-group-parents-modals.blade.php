<!-- Add Group Parent Modal -->
<div wire:ignore.self class="modal fade" id="addGroupParentModal" tabindex="-1" aria-labelledby="addGroupParentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGroupParentModalLabel">Add Group Parent</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createGroupParent'>
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

<!-- Delete Group Parent Modal -->
<div wire:ignore.self class="modal fade" id="deleteGroupParentModal" tabindex="-1" aria-labelledby="deleteGroupParentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteGroupParentModalLabel">Delete Group Parent Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the group `{{ $parentName }}` as a parent from {{ $groupName }}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>
