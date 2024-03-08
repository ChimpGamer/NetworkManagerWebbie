<!-- Add AccountGroup Modal -->
<div wire:ignore.self class="modal fade" id="addAccountGroupModal" tabindex="-1"
     aria-labelledby="addAccountGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAccountGroupModalLabel">Add Account Group</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createAccountGroup'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Name</label>
                        <input type="text" wire:model="groupname" class="form-control">
                        @error('groupname') <span class="text-danger">{{ $message }}</span> @enderror
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

<!-- Edit AccountGroup Modal -->
<div wire:ignore.self class="modal fade" id="editAccountGroupModal" tabindex="-1"
     aria-labelledby="editAccountGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAccountGroupModalLabel">Edit Account Group</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updateAccountGroup'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Name</label>
                        <input type="text" wire:model="groupname" class="form-control">
                        @error('groupname') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Permissions</label>
                        @foreach($permissions as $key => $value)
                            <div class="form-check" wire:key="{{$key}}">
                                <input class="form-check-input" type="checkbox" wire:model="permissions.{{$key}}"
                                       id="permission-{{$key}}">
                                <label class="form-check-label" for="permission-{{$key}}">{{$key}}</label>
                                @error('permissions.' . $key) <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @endforeach
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

<!-- Delete AccountGroup Modal -->
<div wire:ignore.self class="modal fade" id="deleteAccountGroupModal" tabindex="-1"
     aria-labelledby="deleteAccountGroupModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountGroupModalLabel">Delete Account Group Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the {{ $groupname }} group?</p>
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
