<!-- Show Server Modal -->
<div wire:ignore.self class="modal fade" id="showServerModal" tabindex="-1" aria-labelledby="showServerModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showServerModalLabel">Show Server</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Name</strong>
                    <p>{{ $servername }}</p>
                </div>
                <div class="mb-3">
                    <strong>Display Name</strong>
                    <p>{!! $displayname !!}</p>
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
                    <p>{!! $motd !!}</p>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Server Modal -->
<div wire:ignore.self class="modal fade" id="editServerModal" tabindex="-1" aria-labelledby="editServerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editServerModalLabel">Edit Server</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent='updateServer'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Server Name</label>
                        <input type="text" wire:model="servername" class="form-control">
                        @error('servername') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Display Name</label>
                        <input type="text" wire:model="displayname" class="form-control">
                        @error('displayname') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>IP Address</label>
                        <input type="text" wire:model="ip" class="form-control">
                        @error('ip') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Port</label>
                        <input type="text" wire:model="port" class="form-control">
                        @error('port') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>MOTD</label>
                        <input type="text" wire:model="motd" class="form-control">
                        @error('motd') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Allowed Versions</label>
                        <input type="text" wire:model="allowed_versions" class="form-control">
                        @error('allowed_versions') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Restricted</label>
                        <input type="text" wire:model="restricted" class="form-control">
                        @error('restricted') <span class="text-danger">{{ $message }}</span> @enderror
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

<!-- Delete Server Modal -->
<div wire:ignore.self class="modal fade" id="deleteServerModal" tabindex="-1" aria-labelledby="deleteServerModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteServerModalLabel">Delete Server Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the {{ $servername }} server?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>
