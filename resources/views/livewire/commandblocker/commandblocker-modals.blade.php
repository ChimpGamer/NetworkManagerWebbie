<!-- Show CommandBlocker Modal -->
<div wire:ignore.self class="modal fade" id="showCommandBlockerModal" tabindex="-1"
     aria-labelledby="showCommandBlockerModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showCommandBlockerModalLabel">Show Command Block</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Name</strong>
                    <p>{{ $name }}</p>
                </div>
                <div class="mb-3">
                    <strong>Description</strong>
                    <p>{{ $description }}</p>
                </div>
                <div class="mb-3">
                    <strong>Command</strong>
                    <p>{{ $command }}</p>
                </div>
                <div class="mb-3">
                    <strong>Server</strong>
                    <p>{{ $server }}</p>
                </div>
                <div class="mb-3">
                    <strong>Custom Message</strong>
                    <p>{!!  $customMessage  !!}</p>
                </div>
                <div class="mb-3">
                    <label class="bold">Bypass Permission</label>
                    <div class="d-flex">
                        <strong>Off</strong>
                        <div class="form-check form-switch ms-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="silentSwitch"
                                   wire:model="bypasspermission" disabled/>
                            <label class="form-check-label" style="font-weight: bold;"
                                   for="silentSwitch"><strong>On</strong></label>
                        </div>
                    </div>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add CommandBlocker Modal -->
<div wire:ignore.self class="modal fade" id="addCommandBlockerModal" tabindex="-1" aria-labelledby="addCommandBlockerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCommandBlockerModalLabel">Add CommandBlocker</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createCommandBlocker'>
                <div class="modal-body">
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
                        <label class="bold">Command</label>
                        <input type="text" wire:model.live="command" class="form-control">
                        @error('command') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Server</label>
                        <input type="text" wire:model.live="server" class="form-control">
                        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Custom Message</label>
                        <input type="text" wire:model.live="customMessage" class="form-control">
                        @error('customMessage') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Bypass Permission</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="bypasspermissionSwitch"
                                       wire:model.live="bypasspermission" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="bypasspermissionSwitch"><strong>On</strong></label>
                            </div>
                            @error('bypasspermission') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
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

<!-- Update CommandBlocker Modal -->
<div wire:ignore.self class="modal fade" id="editCommandBlockerModal" tabindex="-1" aria-labelledby="editCommandBlockerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommandBlockerModalLabel">Edit CommandBlocker</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updateCommandBlocker'>
                <div class="modal-body">
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
                        <label class="bold">Command</label>
                        <input type="text" wire:model.live="command" class="form-control">
                        @error('command') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Server</label>
                        <input type="text" wire:model.live="server" class="form-control">
                        @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Custom Message</label>
                        <input type="text" wire:model.live="customMessage" class="form-control">
                        @error('customMessage') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">Bypass Permission</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="bypasspermissionSwitch"
                                       wire:model.live="bypasspermission" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="bypasspermissionSwitch"><strong>On</strong></label>
                            </div>
                            @error('bypasspermission') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
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

<!-- Delete CommandBlocker Modal -->
<div wire:ignore.self class="modal fade" id="deleteCommandBlockerModal" tabindex="-1" aria-labelledby="deleteCommandBlockerModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCommandBlockerModalLabel">Delete CommandBlocker Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete blocked command #{{ $commandBlockerId }}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>
