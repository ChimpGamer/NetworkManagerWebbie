<!-- Show Announcement Modal -->
<div wire:ignore.self class="modal fade" id="showAnnouncementModal" tabindex="-1" aria-labelledby="showAnnouncementModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showAnnouncementModalLabel">Show Announcement</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Type</strong>
                    <p>{{ $type }}</p>
                </div>
                <div class="mb-3">
                    <strong>Message</strong>
                    <p>{!! $message !!}</p>
                </div>
                <div class="mb-3">
                    <strong>Sound</strong>
                    <p>{{ $sound }}</p>
                </div>
                <div class="mb-3">
                    <strong>server</strong>
                    <p>{{ $server }}</p>
                </div>
                <div class="mb-3">
                    <strong>Condition</strong>
                    <p>{{  $condition  }}</p>
                </div>
                <div class="mb-3">
                    <strong>Expires</strong>
                    <p>{{  $expires  }}</p>
                </div>
                <div class="mb-3">
                    <strong>Permission</strong>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckCheckedDisabled" @checked(old('permission', $permission)) disabled />
                        <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Permission</label>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Active</strong>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckCheckedDisabled" @checked(old('active', $active)) disabled />
                        <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Active</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Announcement Modal -->
<div wire:ignore.self class="modal fade" id="editAnnouncementModal" tabindex="-1" aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAnnouncementModalLabel">Edit Announcement</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent='updateAnnouncement'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Announcement Type</label>
                        <input type="number" wire:model="type" class="form-control">
                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Message</label>
                        <input type="text" wire:model="message" class="form-control">
                        @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Sound</label>
                        <input type="text" wire:model="sound" class="form-control">
                        @error('sound') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Server</label>
                        <input type="text" wire:model="server" class="form-control">
                        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Condition</label>
                        <input type="text" wire:model="condition" class="form-control">
                        @error('condition') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Expires</label>
                        <input type="datetime-local" wire:model="expires" class="form-control">
                        @error('expires') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Permission</label>
                        <input type="text" wire:model="permission" class="form-control">
                        @error('permission') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Active</label>
                        <input type="text" wire:model="active" class="form-control">
                        @error('active') <span class="text-danger">{{ $message }}</span> @enderror
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
