@php use Illuminate\Database\Eloquent\Collection; @endphp
    <!-- Show Server Group Modal -->
<x-modal id="showServerGroupModal" title="Show Server Group">
    <div class="mb-3">
        <strong>ID</strong>
        <p>{{ $groupId }}</p>
    </div>
    <div class="mb-3">
        <strong>Name</strong>
        <p>{{ $groupname }}</p>
    </div>
    <div class="mb-3">
        <strong>Balance Method</strong>
        <p>{{ $balancemethod }}</p>
    </div>
    <div class="mb-3">
        <strong>Servers</strong>
        @foreach($currentServers as $server)
            <p class="mb-0">{{ $server->servername }}</p>
        @endforeach
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
    </x-slot>
</x-modal>

<!-- Delete Server Group Modal -->
<x-modal id="deleteServerGroupModal" title="Delete Server Group">
    <p>Are you sure you want to delete the {{ $groupname }} server group?</p>
    <x-slot name="footer">
        <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
        <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">
            Yes, Delete
        </button>
    </x-slot>
</x-modal>

<!-- Update Server Modal -->
<x-modal id="editServerGroupModal" title="Edit Server Group" :hasForm="true" wire:submit.prevent='updateServerGroup'>
    <div class="mb-3">
        <label class="bold">Group Name</label>
        <input type="text" wire:model.live="groupname" class="form-control">
        @error('groupname') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Balance Method</label>
        <select class="form-control" wire:model.live="balancemethod">
            @foreach ($balancemethods as $balancemethodchoice)
                <option
                    value="{{ $balancemethodchoice }}" {{ $balancemethodchoice == $balancemethod ? 'selected' : '' }}>{{ $balancemethodchoice }}</option>
            @endforeach
        </select>
        @error('balancemethod') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Servers</label>
        <select style="width: 100%" title="servers" multiple wire:model.live="serversSelection">
            @foreach ($this->allServers as $server)
                <option
                    value="{{ $server->id }}" {{ $currentServers instanceof Collection && $currentServers->contains($server) ? 'selected' : '' }}>{{ $server->servername }}</option>
            @endforeach
        </select>
        @error('serversSelection') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal" data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </x-slot>
</x-modal>

<!-- Add Server Group Modal -->
<x-modal id="addServerGroupModal" title="Add Server Group" :hasForm="true" wire:submit.prevent='createServerGroup'>
    <div class="mb-3">
        <label class="bold">Group Name</label>
        <input type="text" wire:model.live="groupname" class="form-control">
        @error('groupname') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Balance Method</label>
        <select class="form-control" wire:model.live="balancemethod">
            <option value="" disabled selected hidden="until-found">Select a method</option>
            @foreach ($balancemethods as $balancemethod)
                <option value="{{ $balancemethod }}">{{ $balancemethod }}</option>
            @endforeach
        </select>
        @error('balancemethod') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3">
        <label class="bold">Servers</label>
        <select style="width: 100%" title="servers" multiple wire:model.live="serversSelection">
            @foreach ($this->allServers as $server)
                <option value="{{ $server->id }}">{{ $server->servername }}</option>
            @endforeach
        </select>
        @error('serversSelection') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close
        </button>
        <button type="submit" class="btn btn-primary">Add</button>
    </x-slot>
</x-modal>
