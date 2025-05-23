<div>
    @include('livewire.announcements.announcement-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Announcements">
        <livewire:announcements.announcements-table/>
    </x-card-table>
    @can('edit_announcements')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#addAnnouncementModal"
                    wire:click="addAnnouncement">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Announcement
            </button>
        </div>
    @endcan
</div>
