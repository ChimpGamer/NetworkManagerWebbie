<!-- Add Account Modal -->
<div wire:ignore.self class="modal fade" id="addAccountModal" tabindex="-1"
     aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAccountModalLabel">Add Account</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createAccount'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Username</label>
                        <input type="text" wire:model="username" class="form-control">
                        @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Password</label>
                        <input type="password" wire:model="password" name="password" class="form-control">
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Confirm Password</label>
                        <input type="password" wire:model="password_confirmation" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="bold">UserGroup</label>
                        <select class="form-control" wire:model="user_group">
                            <option selected>Select a user group...</option>

                            @foreach ($this->allUserGroups as $group)
                                <option value="{{ $group->name }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        @error('user_group') <span class="text-danger">{{ $message }}</span> @enderror
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

<!-- Edit Account Modal -->
<div wire:ignore.self class="modal fade" id="editAccountModal" tabindex="-1"
     aria-labelledby="editAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAccountModalLabel">Edit Account</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updateAccount'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Username</label>
                        <input type="text" wire:model="username" class="form-control">
                        @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">UserGroup</label>
                        <select class="form-control" wire:model="user_group">
                            <option disabled>Select a user group...</option>

                            @foreach ($this->allUserGroups as $group)
                                <option value="{{ $group->name }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        @error('user_group') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div wire:ignore.self class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete {{ $username }}'s account?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>
