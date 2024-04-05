<!-- Show Server Group Modal -->
<div wire:ignore.self class="modal fade" id="showServerGroupModal" tabindex="-1" aria-labelledby="showServerGroupModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showServerGroupModalLabel">Show Server Group</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                    @foreach($servers as $server)
                        <p class="mb-0">{{ $server->servername }}</p>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Server Group Modal -->
<div wire:ignore.self class="modal fade" id="deleteServerGroupModal" tabindex="-1" aria-labelledby="deleteServerGroupModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteServerGroupModalLabel">Delete Server Group Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the {{ $groupname }} server group?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Server Group Modal -->
<div wire:ignore.self class="modal fade" id="addServerGroupModal" tabindex="-1" aria-labelledby="addServerGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServerGroupModalLabel">Add Server Group</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createServerGroup'>
                <div class="modal-body">
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
                            @foreach ($servers as $server)
                                <option value="{{ $server->id }}">{{ $server->servername }}</option>
                            @endforeach
                        </select>
                        @error('serversSelection') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
