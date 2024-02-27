<div>
    @include('livewire.announcements.announcement-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header h5">
            Announcements
            <label for="announcementSearch" class="float-end mx-2">
                <input id="announcementSearch" type="search" wire:model.live="search" class="form-control"
                       placeholder="Search..."/>
            </label>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="announcementsTable" class="table text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th style="width: 65%">Message</th>
                    <th>Expires</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach($announcements as $announcement)
                    <tr>
                        <td>{{ $announcement->id }}</td>
                        <td>{!! $announcement->message !!}</td>
                        <td>
                            @if ($announcement->expires != null)
                                <i class="fas fa-check-circle fa-lg" style="color:green" data-mdb-tooltip-init
                                   title="{{ $announcement->expires }}"></i>
                            @else
                                <i class="fas fa-xmark-circle fa-lg" style="color:red"></i>
                            @endif
                        </td>
                        <td>
                            @if ($announcement->active)
                                <i class="fas fa-check-circle fa-lg" style="color:green"></i>
                            @else
                                <i class="fas fa-xmark-circle fa-lg" style="color:red"></i>
                            @endif
                        </td>
                        <th>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#showAnnouncementModal"
                                    wire:click="showAnnouncement({{$announcement->id}})">
                                <i class="material-icons text-info">info</i>
                            </button>
                            @can('edit_announcements')
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#editAnnouncementModal"
                                        wire:click="editAnnouncement({{$announcement->id}})">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#deleteAnnouncementModal"
                                        wire:click="deleteAnnouncement({{ $announcement->id }})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            @endcan
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
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
