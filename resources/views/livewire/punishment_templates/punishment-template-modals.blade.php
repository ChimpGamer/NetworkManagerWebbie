<!-- Show Punishment Modal -->
<x-modal id="showPunishmentTemplateModal" title="Show Punishment Template">
    <div class="mb-3">
        <strong>Name</strong>
        <p>{{ $name }}</p>
    </div>
    <div class="mb-3">
        <strong>Type</strong>
        <p>{{ $type }}</p>
    </div>
    <div class="mb-3">
        <strong>Duration (Seconds)</strong>
        <p>{{ $duration }}</p>
    </div>
    <div class="mb-3">
        <strong>Server</strong>
        <p>{{ $server }}</p>
    </div>
    <div class="mb-3">
        <strong>Reason</strong>
        <p>{!! \App\Helpers\HtmlSanitizer::sanitize($reason) !!}</p>
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
    </x-slot>
</x-modal>

<!-- Add Punishment Template Modal -->
<x-modal id="addPunishmentTemplateModal" title="Add Punishment Template" :hasForm="true" wire:submit.prevent="createTemplate">
    <div class="mb-3">
        <label class="bold">Name</label>
        <input type="text" wire:model="name" class="form-control">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Type</label>
        <select name="type" class="form-control" wire:model.change="typeId">
            @foreach($this->punishmentTypeCases as $punishmentType)
                <option value="{{$punishmentType}}">{{ $punishmentType->name() }}</option>
            @endforeach
        </select>
        @error('typeId') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    @if($isTemporary)
        <div class="mb-3">
            <label class="bold">Duration (Seconds)</label>
            <input type="number" wire:model="duration" class="form-control" placeholder="Duration in seconds...">
            @error('duration') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    @endif
    @if(!$isGlobal)
        <div class="mb-3">
            <label class="bold">Server</label>
            <input type="text" wire:model="server" class="form-control">
            @error('server') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    @endif
    <div class="mb-3">
        <label class="bold">Reason</label>
        <input type="text" wire:model="reason" class="form-control">
        @error('reason') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal" data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
    </x-slot>
</x-modal>

<!-- Update Punishment Modal -->
<x-modal id="editPunishmentTemplateModal" title="Edit Punishment Template" :hasForm="true" wire:submit.prevent="updateTemplate">
    <div class="mb-3">
        <label class="bold">Name</label>
        <input type="text" wire:model="name" class="form-control">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Type</label>
        <select name="type" class="form-control" wire:model.change="typeId">
            @foreach($this->punishmentTypeCases as $punishmentType)
                <option
                    value="{{$punishmentType}}">{{ $punishmentType->name() }}</option>
            @endforeach
        </select>
        @error('typeId') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    @if($isTemporary)
        <div class="mb-3">
            <label class="bold">Duration (Seconds)</label>
            <input type="number" wire:model="duration" class="form-control" placeholder="Duration in seconds...">
            @error('duration') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    @endif
    @if(!$isGlobal)
        <div class="mb-3">
            <label class="bold">Server</label>
            <input type="text" wire:model="server" class="form-control">
            @error('server') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    @endif
    <div class="mb-3">
        <label class="bold">Reason</label>
        <input type="text" wire:model="reason" class="form-control">
        @error('reason') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal" data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </x-slot>
</x-modal>

<!-- Delete Punishment Template Modal -->
<x-modal id="deletePunishmentTemplateModal" title="Delete Punishment Template Confirm">
    <p>Are you sure you want to delete punishment template {{ $deleteId }} ({{ $deleteName }})?</p>
    <x-slot name="footer">
        <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
        <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">
            Yes, Delete
        </button>
    </x-slot>
</x-modal>
