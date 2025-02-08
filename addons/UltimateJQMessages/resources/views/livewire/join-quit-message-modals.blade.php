<!-- Add Join Quit Message Modal -->
<div wire:ignore.self class="modal fade" id="addJQMessageModal" tabindex="-1" aria-labelledby="addJQMessageModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addJQMessageModalLabel">Add Join Quit Message</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createJQMessage'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Name</label>
                        <input type="text" wire:model.live="name" class="form-control">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Type</label>
                        <select name="type" class="form-control" wire:model.live="type">
                            @foreach($this->JoinQuitMessageTypeCases as $joinQuitMessageType)
                                <option
                                    value="{{$joinQuitMessageType}}">{{ $joinQuitMessageType->name() }}</option>
                            @endforeach
                        </select>
                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Message</label>
                        <input type="text" wire:model.live="message" class="form-control">
                        @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Permission</label>
                        <input type="text" wire:model.live="permission" class="form-control">
                        @error('permission') <span class="text-danger">{{ $message }}</span> @enderror
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

<!-- Update Join Quit Message Modal -->
<div wire:ignore.self class="modal fade" id="editJQMessageModal" tabindex="-1" aria-labelledby="editJQMessageModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJQMessageModalLabel">Edit Join Quit Message</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updateJQMessage'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Name</label>
                        <input type="text" wire:model.live="name" class="form-control">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Type</label>
                        <select name="type" class="form-control" wire:model.live="type">
                            @foreach($this->JoinQuitMessageTypeCases as $joinQuitMessageType)
                                <option
                                    value="{{$joinQuitMessageType}}">{{ $joinQuitMessageType->name() }}</option>
                            @endforeach
                        </select>
                        @error('typeId') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Message</label>
                        <input type="text" wire:model.live="message" class="form-control">
                        @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Permission</label>
                        <input type="text" wire:model.live="permission" class="form-control">
                        @error('permission') <span class="text-danger">{{ $message }}</span> @enderror
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

<!-- Delete Join Quit Message Modal -->
<div wire:ignore.self class="modal fade" id="deleteJQMessageModal" tabindex="-1"
     aria-labelledby="deleteJQMessageModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteJQMessageModalLabel">Delete Join Quit Message Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete join quit message {{ $name }}?</p>
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
