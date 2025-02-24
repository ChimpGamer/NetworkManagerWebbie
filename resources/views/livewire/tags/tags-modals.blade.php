<!-- Add Tag Modal -->
<x-modal id="addTagModal" title="Add Tag" :hasForm="true" wire:submit.prevent="createTag">
    <div class="mb-3">
        <label class="bold">Name</label>
        <input type="text" wire:model.live="name" class="form-control">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Tag</label>
        <input type="text" wire:model.live="tag" class="form-control">
        @error('tag') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Description</label>
        <input type="text" wire:model.live="description" class="form-control">
        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Server</label>
        <input type="text" wire:model.live="server" class="form-control">
        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
    </x-slot>
</x-modal>

<!-- Update Tag Modal -->
<x-modal id="editTagModal" title="Edit Tag" :hasForm="true" wire:submit.prevent="updateTag">
    <div class="mb-3">
        <label class="bold">Name</label>
        <input type="text" wire:model.live="name" class="form-control">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Tag</label>
        <input type="text" wire:model.live="tag" class="form-control">
        @error('tag') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Description</label>
        <input type="text" wire:model.live="description" class="form-control">
        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Server</label>
        <input type="text" wire:model.live="server" class="form-control">
        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </x-slot>
</x-modal>

<!-- Delete Tag Modal -->
<x-modal id="deleteTagModal" title="Delete Tag Confirm">
    <p>Are you sure you want to delete tag {{ $name }}?</p>
    <x-slot name="footer">
        <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
        <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">
            Yes, Delete
        </button>
    </x-slot>
</x-modal>
