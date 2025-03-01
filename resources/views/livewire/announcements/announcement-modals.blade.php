<!-- Show Announcement Modal -->
<div wire:ignore.self class="modal fade" id="showAnnouncementModal" tabindex="-1"
     aria-labelledby="showAnnouncementModalLabel"
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
                    <p>{{ $typeName }}</p>
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
                    <strong>Server</strong>
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
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="flexSwitchCheckCheckedDisabled" @checked(old('permission', $permission)) disabled/>
                        <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Permission</label>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Active</strong>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="flexSwitchCheckCheckedDisabled" @checked(old('active', $active)) disabled/>
                        <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Active</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn"
                   @if(!empty($message)) href="https://webui.advntr.dev/?mode=chat_closed&input={{urlencode($message)}}"
                   target="_blank" rel="noopener noreferrer" @endif>
                    Click to preview
                </a>
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Announcement Modal -->
<div wire:ignore.self class="modal fade" id="addAnnouncementModal" tabindex="-1"
     aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAnnouncementModalLabel">Add Announcement</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createAnnouncement'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Announcement Type</label>
                        <select name="type" class="form-control" wire:model.live="type">
                            @foreach(\App\Models\AnnouncementType::cases() as $announcementType)
                                <option
                                    value="{{$announcementType}}">{{ $announcementType->name() }}</option>
                            @endforeach
                        </select>
                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Message</label>
                        <textarea wire:model="message" class="form-control"></textarea>
                        @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Sound</label>
                        <input type="text" wire:model="sound" class="form-control">
                        @error('sound') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if($showServerInput)
                        <div class="mb-3">
                            <label>Server</label>
                            <input type="text" wire:model="server" class="form-control">
                            @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
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
                        <label class="bold">Permission</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="permissionSwitch"
                                       wire:model="permission" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="permissionSwitch"><strong>On</strong></label>
                            </div>
                            @error('permission') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bold">Active</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="activeSwitch"
                                       wire:model="active" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="activeSwitch"><strong>On</strong></label>
                            </div>
                            @error('active') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn"
                       @if(!empty($message)) href="https://webui.advntr.dev/?mode=chat_closed&input={{urlencode($message)}}"
                       target="_blank" rel="noopener noreferrer" @endif>
                        Click to preview
                    </a>
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">Close
                    </button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Announcement Modal -->
<div wire:ignore.self class="modal fade" id="editAnnouncementModal" tabindex="-1"
     aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAnnouncementModalLabel">Edit Announcement</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updateAnnouncement'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Announcement Type</label>
                        <select name="type" class="form-control" wire:model.live="type">
                            @foreach(\App\Models\AnnouncementType::cases() as $announcementType)
                                <option
                                    value="{{$announcementType}}">{{ $announcementType->name() }}</option>
                            @endforeach
                        </select>
                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Message</label>
                        <textarea wire:model="message" class="form-control"></textarea>
                        @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Sound</label>
                        <input type="text" wire:model="sound" class="form-control">
                        @error('sound') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if($showServerInput)
                        <div class="mb-3">
                            <label>Server</label>
                            <input type="text" wire:model="server" class="form-control">
                            @error('server') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
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
                        <label class="bold">Permission</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="permissionSwitch"
                                       wire:model="permission" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="permissionSwitch"><strong>On</strong></label>
                            </div>
                            @error('permission') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="bold">Active</label>
                        <div class="d-flex">
                            <strong>Off</strong>
                            <div class="form-check form-switch ms-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="activeSwitch"
                                       wire:model="active" />
                                <label class="form-check-label" style="font-weight: bold;"
                                       for="activeSwitch"><strong>On</strong></label>
                            </div>
                            @error('active') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn"
                       @if(!empty($message)) href="https://webui.advntr.dev/?mode=chat_closed&input={{urlencode($message)}}"
                       target="_blank" rel="noopener noreferrer" @endif>
                        Click to preview
                    </a>
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">Close
                    </button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Announcement Modal -->
<div wire:ignore.self class="modal fade" id="deleteAnnouncementModal" tabindex="-1"
     aria-labelledby="deleteAnnouncementModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAnnouncementModalLabel">Delete Announcement Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete announcement {{ $deleteId }}?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close
                </button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal"
                        data-mdb-dismiss="modal">Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>
