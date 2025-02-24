<!-- Show CommandBlocker Modal -->
<x-modal id="showFilterModal" title="Show Filter">
    <div class="mb-3">
        <strong>Name</strong>
        <p>{{ $name }}</p>
    </div>
    <div class="mb-3">
        <strong>Description</strong>
        <p>{{ $description }}</p>
    </div>
    <div class="mb-3">
        <strong>Word</strong>
        <p>{{ $word }}</p>
    </div>
    <div class="mb-3">
        <strong>Replacement</strong>
        <p>{{ $replacement }}</p>
    </div>
    <div class="mb-3">
        <strong>Server</strong>
        <p>{{ $server }}</p>
    </div>
    <div class="mb-3">
        <label class="bold">Enabled</label>
        <div class="d-flex">
            <strong>Off</strong>
            <div class="form-check form-switch ms-2">
                <input class="form-check-input" type="checkbox" role="switch" id="silentSwitch"
                       wire:model="enabled" disabled/>
                <label class="form-check-label" style="font-weight: bold;"
                       for="silentSwitch"><strong>On</strong></label>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
    </x-slot>
</x-modal>

<!-- Add Filter Modal -->
<x-modal id="addFilterModal" title="Add Filter" :hasForm="true" wire:submit.prevent="createFilter">
    <div class="mb-3">
        <label class="bold">Name</label>
        <input type="text" wire:model.live="name" class="form-control">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Description</label>
        <input type="text" wire:model.live="description" class="form-control">
        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Word</label>
        <input type="text" wire:model.live="word" class="form-control">
        @error('word') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Replacement</label>
        <input type="text" wire:model.live="replacement" class="form-control">
        @error('replacement') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Server</label>
        <input type="text" wire:model.live="server" class="form-control">
        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Enabled</label>
        <div class="d-flex">
            <strong>Off</strong>
            <div class="form-check form-switch ms-2">
                <input class="form-check-input" type="checkbox" role="switch" id="enabledSwitch"
                       wire:model.live="enabled" />
                <label class="form-check-label" style="font-weight: bold;"
                       for="enabledSwitch"><strong>On</strong></label>
            </div>
            @error('enabled') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
    </x-slot>
</x-modal>

<!-- Update Filter Modal -->
<x-modal id="editFilterModal" title="Edit Filter" :hasForm="true" wire:submit.prevent="updateFilter">
    <div class="mb-3">
        <label class="bold">Name</label>
        <input type="text" wire:model.live="name" class="form-control">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Description</label>
        <input type="text" wire:model.live="description" class="form-control">
        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Word</label>
        <input type="text" wire:model.live="word" class="form-control">
        @error('word') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Replacement</label>
        <input type="text" wire:model.live="replacement" class="form-control">
        @error('replacement') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Server</label>
        <input type="text" wire:model.live="server" class="form-control">
        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Enabled</label>
        <div class="d-flex">
            <strong>Off</strong>
            <div class="form-check form-switch ms-2">
                <input class="form-check-input" type="checkbox" role="switch" id="enabledSwitch"
                       wire:model.live="enabled" />
                <label class="form-check-label" style="font-weight: bold;"
                       for="enabledSwitch"><strong>On</strong></label>
            </div>
            @error('enabled') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </x-slot>
</x-modal>

<!-- Delete Filter Modal -->
<x-modal id="deleteFilterModal" title="Delete Filter Confirm">
    <p>Are you sure you want to delete filter #{{ $filterId }}?</p>
    <x-slot name="footer">
        <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
        <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">
            Yes, Delete
        </button>
    </x-slot>
</x-modal>
