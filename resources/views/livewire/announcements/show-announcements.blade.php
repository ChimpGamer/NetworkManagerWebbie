<div>
    @include('livewire.announcements.announcement-modals')

    @if (session()->has('message'))
    <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header h5">
            Announcements
            <label for="announcementSearch" class="float-end mx-2">
                <input id="announcementSearch" type="search" wire:model="search" class="form-control"
                    placeholder="Search..." />
            </label>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="announcementsTable" class="table text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Message</th>
                        <th>Expires</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($announcements as $announcement)
                    <tr>
                        <td>{{ $announcement->id }}</td>
                        <td>{{ $announcement->type }}</td>
                        <td>{!! $announcement->message !!}</td>
                        <td>{{ $announcement->expires }}</td>
                        <td>
                            <span @class(['label', 'label-success' => $announcement->active, 'label-danger' => ! $announcement->active])>
                                @if ($announcement->active)
                                    True
                                @else
                                    False
                                @endif
                            </span>
                        </td>
                        <th>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal" data-mdb-target="#showAnnouncementModal"
                                wire:click="showAnnouncement({{$announcement->id}})">
                                <i class="material-icons text-info">info</i>
                            </button>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal" data-mdb-target="#editAnnouncementModal"
                                wire:click="editAnnouncement({{$announcement->id}})">
                                <i class="material-icons text-warning">edit</i>
                            </button>
                            <button type="button" style="background: transparent; border: none;">
                                <i class="material-icons text-danger">delete</i>
                            </button>
                        </th>
                        {{--<th><button class="viewDetails" type="button" data-id="{{ $server->id  }}">View</button>
                        </th>--}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $announcements->links() }}
        </div>
    </div>

</div>
