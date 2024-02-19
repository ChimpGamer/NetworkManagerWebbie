<!-- Show Permission Player Modal -->
<div wire:ignore.self class="modal fade" id="showPermissionPlayerModal" tabindex="-1" aria-labelledby="showPermissionPlayerModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showPermissionPlayerModalLabel">Show Permission Player</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>UUID</strong>
                    <p>{{ $playerUuid }}</p>
                </div>
                <div class="mb-3">
                    <strong>Name</strong>
                    <p>{{ $name }}</p>
                </div>
                <div class="mb-3">
                    <strong>Prefix</strong>
                    <p>{{ $prefix }}</p>
                </div>
                <div class="mb-3">
                    <strong>Suffix</strong>
                    <p>{{ $suffix }}</p>
                </div>
                <div class="mb-3">
                    <strong>Actions</strong>
                    <p>
                        <a type="button" class="btn" href="/permissions/player/{{$playerUuid}}/permissions">View Permissions</a>
                        <a type="button" class="btn" href="/permissions/player/{{$playerUuid}}/groups">View Groups</a>
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Permission Player Modal -->
<div wire:ignore.self class="modal fade" id="editPermissionPlayerModal" tabindex="-1" aria-labelledby="editPermissionPlayerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPermissionPlayerModalLabel">Edit Permission Player {{$name}}</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updatePermissionPlayer'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">Prefix</label>
                        <input type="text" wire:model.live="prefix" class="form-control">
                        @error('prefix') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Suffix</label>
                        <input type="text" wire:model.live="suffix" class="form-control">
                        @error('suffix') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
