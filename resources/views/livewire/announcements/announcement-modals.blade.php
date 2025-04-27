<!-- Show Announcement Modal -->
<x-modal id="showAnnouncementModal" title="Show Announcement">
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
        <label>Interval (Overwrites global interval)</label>
        <p>{{  $interval  }}</p>
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
    <x-slot name="footer">
        <a type="button" class="btn"
           @if(!empty($message)) href="https://webui.advntr.dev/?mode=chat_closed&input={{urlencode($message)}}"
           target="_blank" rel="noopener noreferrer" @endif>
            Click to preview
        </a>
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close</button>
    </x-slot>
</x-modal>

<!-- Add Announcement Modal -->
<x-modal id="addAnnouncementModal" title="Add Announcement" :hasForm="true" wire:submit.prevent="createAnnouncement">
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
        <label>Interval (Overwrite global interval)</label>
        <input type="text" wire:model="interval" class="form-control">
        @error('interval') <span class="text-danger">{{ $message }}</span> @enderror
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
                       wire:model="permission"/>
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
                       wire:model="active"/>
                <label class="form-check-label" style="font-weight: bold;"
                       for="activeSwitch"><strong>On</strong></label>
            </div>
            @error('active') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
    </x-slot>
</x-modal>

<!-- Update Announcement Modal -->
<x-modal id="editAnnouncementModal" title="Edit Announcement" :hasForm="true" wire:submit.prevent="updateAnnouncement">
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
        <label>Interval (Overwrite global interval)</label>
        <input type="text" wire:model="interval" class="form-control">
        @error('interval') <span class="text-danger">{{ $message }}</span> @enderror
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
                       wire:model="permission"/>
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
                       wire:model="active"/>
                <label class="form-check-label" style="font-weight: bold;"
                       for="activeSwitch"><strong>On</strong></label>
            </div>
            @error('active') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
    <x-slot name="footer">
        <a type="button" class="btn"
           @if(!empty($message)) href="https://webui.advntr.dev/?mode=chat_closed&input={{urlencode($message)}}"
           target="_blank" rel="noopener noreferrer" @endif>
            Click to preview
        </a>
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </x-slot>
</x-modal>

<!-- Delete Announcement Modal -->
<x-modal id="deleteAnnouncementModal" title="Delete Announcement Confirm">
    <p>Are you sure you want to delete announcement {{ $deleteId }}?</p>
    <x-slot name="footer">
        <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
        <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">
            Yes, Delete
        </button>
    </x-slot>
</x-modal>
