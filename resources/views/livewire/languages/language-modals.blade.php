<!-- Add Language Modal -->
<x-modal id="addLanguageModal" title="Add Language" :hasForm="true" wire:submit.prevent="createLanguage">
    <div class="mb-3">
        <label class="bold">Language Name</label>
        <input type="text" wire:model.live="name" class="form-control">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
    </x-slot>
</x-modal>

<!-- Delete Language Modal -->
<x-modal id="deleteFilterModal" title="Delete Language Confirm">
    <p>Are you sure you want to delete the {{ $name }} language?</p>
    <x-slot name="footer">
        <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
        <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
    </x-slot>
</x-modal>

<!-- Add Language Message Modal -->
<x-modal id="addLanguageMessageModal" title="Add Language Message" :hasForm="true" wire:submit.prevent="addLanguageMessage">
    <div class="mb-3">
        <label class="bold">Unique Key</label>
        <input type="text" wire:model.live="key" class="form-control">
        @error('key') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Plugin</label>
        <input type="text" wire:model.live="plugin" class="form-control">
        @error('plugin') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Version</label>
        <input type="text" wire:model.live="version" class="form-control">
        @error('version') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
    </x-slot>
</x-modal>
