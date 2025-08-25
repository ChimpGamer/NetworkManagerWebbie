<!-- Show Server Modal -->
<x-modal id="showServerModal" title="Show Server">
    <div class="mb-3">
        <strong>Name</strong>
        <p>{{ $servername }}</p>
    </div>
    <div class="mb-3">
        <strong>Display Name</strong>
        <p>{!! \App\Helpers\HtmlSanitizer::sanitize($displayname) !!}</p>
    </div>
    <div class="mb-3">
        <strong>IP Address</strong>
        <p>{{ $ip }}</p>
    </div>
    <div class="mb-3">
        <strong>Port</strong>
        <p>{{ $port }}</p>
    </div>
    <div class="mb-3">
        <strong>MOTD</strong>
        <p>{!! \App\Helpers\HtmlSanitizer::sanitize($motd) !!}</p>
    </div>
    <div class="mb-3">
        <strong>Allowed Versions</strong>
        <p>{{ $allowed_versions }}</p>
    </div>
    <div class="mb-3">
        <strong>Restricted</strong>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckCheckedDisabled" @checked(old('restricted', $restricted)) disabled />
            <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Restricted</label>
        </div>
    </div>
    <div class="mb-3">
        <strong>Online</strong>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckCheckedDisabled" @checked(old('online', $online)) disabled />
            <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Online Status</label>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
    </x-slot>
</x-modal>

<!-- Update Server Modal -->
<x-modal id="editServerModal" title="Edit Server" :hasForm="true" wire:submit.prevent="updateServer">
    <div class="mb-3">
        <label class="bold">Server Name</label>
        <input type="text" wire:model.live="servername" class="form-control">
        @error('servername') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Display Name</label>
        <input type="text" wire:model.live="displayname" class="form-control">
        @error('displayname') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">IP Address</label>
        <input type="text" wire:model.live="ip" class="form-control">
        @error('ip') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Port</label>
        <input type="text" wire:model.live="port" class="form-control">
        @error('port') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">MOTD</label>
        <textarea wire:model.live="motd" class="form-control"></textarea>
        @error('motd') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Allowed Versions</label>
        <input type="text" wire:model.live="allowed_versions" class="form-control">
        @error('allowed_versions') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" wire:model.live="restricted" />
            <label class="form-check-label" style="font-weight: bold;" for="flexSwitchCheckChecked">Restricted</label>
        </div>
        @error('restricted') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </x-slot>
</x-modal>

<!-- Delete Server Modal -->
<x-modal id="deleteServerModal" title="Delete Server Confirm">
    <p>Are you sure you want to delete the {{ $servername }} server?</p>
    <x-slot name="footer">
        <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
        <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">
            Yes, Delete
        </button>
    </x-slot>
</x-modal>

<!-- Add Server Modal -->
<x-modal id="addServerModal" title="Add Server" :hasForm="true" wire:submit.prevent="createServer">
    <div class="mb-3">
        <label class="bold">Server Name</label>
        <input type="text" wire:model.live="servername" class="form-control">
        @error('servername') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Display Name</label>
        <input type="text" wire:model.live="displayname" class="form-control">
        @error('displayname') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">IP Address</label>
        <input type="text" wire:model.live="ip" class="form-control">
        @error('ip') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Port</label>
        <input type="text" wire:model.live="port" class="form-control">
        @error('port') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">MOTD</label>
        <textarea wire:model.live="motd" class="form-control"></textarea>
        @error('motd') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Allowed Versions</label>
        <input type="text" wire:model.live="allowed_versions" class="form-control">
        @error('allowed_versions') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" wire:model.live="restricted" />
            <label class="form-check-label bold" for="flexSwitchCheckChecked">Restricted</label>
        </div>
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
    </x-slot>
</x-modal>
