<div>
    @include('livewire.announcements.announcement-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row mt-2 align-items-center text-center">
                <div class="col-md-12">
                    <h5 class="mb-0">
                        <strong>Announcements</strong>
                    </h5>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <livewire:announcements.announcements-table/>
        </div>
    </div>

    {{--<div class="card">
        <div class="card-header">
            <div class="row mt-2 justify-content-between text-center">
                <div class="col-md-auto me-auto">
                    <label>Show
                        <select class="form-select form-select-sm" style="display: inherit; width: auto" wire:model.live="per_page">
                            <option value=10>10</option>
                            <option value=25>25</option>
                            <option value=50>50</option>
                            <option value=100>100</option>
                        </select>
                        entries
                    </label>
                </div>
                <div class="col-md-auto">
                    <h5 class="mb-0 text-center">
                        <strong>Announcements</strong>
                    </h5>
                </div>
                <div class="col-md-auto ms-auto" wire:ignore>
                    <div class="form-outline w-auto d-inline-block" data-mdb-input-init>
                        <input type="search" id="announcementSearch" class="form-control form-control-sm" wire:model.live="search"/>
                        <label class="form-label" for="announcementSearch" style="font-family: Roboto, 'FontAwesome'">Search...</label>
                    </div>
                </div>
            </div>
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
                @forelse($announcements as $announcement)
                    <tr>
                        <td>{{ $announcement->id }}</td>
                        <td>{!! $announcement->message !!}</td>
                        <td>
                            @if ($announcement->expires != null)
                                <i class="fas fa-check-circle fa-lg" style="color:green" x-data
                                   x-tooltip.raw="{{ $announcement->expires }}"></i>
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
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init
                                    data-mdb-modal-init
                                    data-mdb-target="#showAnnouncementModal"
                                    wire:click="showAnnouncement({{$announcement->id}})">
                                <i class="material-icons text-info">info</i>
                            </button>
                            @can('edit_announcements')
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init
                                        data-mdb-target="#editAnnouncementModal"
                                        wire:click="editAnnouncement({{$announcement->id}})">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init
                                        data-mdb-target="#deleteAnnouncementModal"
                                        wire:click="deleteAnnouncement({{ $announcement->id }})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            @endcan
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $announcements->links() }}
        </div>
    </div>--}}
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
