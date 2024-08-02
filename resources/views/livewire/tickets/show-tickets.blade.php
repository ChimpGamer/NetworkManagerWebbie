<div>

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
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
                        <strong>Tickets</strong>
                    </h5>
                </div>
                <div class="col-md-auto ms-auto" wire:ignore>
                    <div class="form-outline w-auto d-inline-block" data-mdb-input-init>
                        <input type="search" id="ticketsSearch" class="form-control form-control-sm" wire:model.live="search"/>
                        <label class="form-label" for="ticketsSearch" style="font-family: Roboto, 'FontAwesome'">Search...</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="serversTable" class="table text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Creator</th>
                    <th>Title</th>
                    <th>Assigned</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>@if ($ticket->active)
                                <i class="fas fa-check-circle fa-lg text-success"></i>
                            @else
                                <i class="fas fa-exclamation-circle fa-lg text-danger"></i>
                            @endif {{ $ticket->id }}</td>
                        <td>{{ $ticket->getCreatorName() }}</td>
                        <td>{{ $ticket->title }}</td>
                        <td>@if(empty($ticket->assigned_to))
                                Unassigned
                            @else
                                {{ $ticket->assigned_to }}
                            @endif</td>
                        <td>{{ $ticket->getCreationFormatted() }}</td>
                        <td>{{ $ticket->getLastUpdateFormatted() }}</td>
                        <th>
                            <a type="button" style="background: transparent; border: none;" href="/tickets/{{$ticket->id}}">
                                <i class="material-icons text-info">info</i>
                            </a>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $tickets->links() }}
        </div>
    </div>
</div>
