<!-- Edit Language Message Modal -->
<x-modal id="editLanguageMessageModal" title="Edit Language Message {{ $languageMessageKey }}" :hasForm="true" wire:submit.prevent="updateLanguageMessage">
    <div class="mb-3">
        <label class="bold">Message</label>
        <input type="text" wire:model.live="languageMessage" class="form-control">
        @error('languageMessage') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </x-slot>
</x-modal>
