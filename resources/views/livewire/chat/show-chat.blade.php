<div>
    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0 text-center">
                <strong>Chat</strong>
            </h5>

            <div class="d-inline">
                <label>Type:
                    <select class="form-select" style="display: inherit; width: auto" wire:model.live="type">
                        <option value="1">Chat</option>
                        <option value="2">PM</option>
                        <option value="3">Party</option>
                    </select>
                </label>
            </div>
            <div class="float-end d-inline" wire:ignore>
                <div class="form-outline" data-mdb-input-init>
                    <input type="search" id="chatSearch" class="form-control" wire:model.live="search"/>
                    <label class="form-label" for="chatSearch" style="font-family: Roboto, 'FontAwesome'">Search...</label>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Server</th>
                    <th>Time</th>
                </tr>
                </thead>
                <tbody>
                @forelse($chatmessages as $chatMessage)
                    <tr>
                        <td><a href="/players/{{ $chatMessage->uuid }}"><img src="https://minotar.net/avatar/{{ $chatMessage->uuid }}/20" alt="requester avatar"> {{ $chatMessage->username }}</a></td>
                        <td>{{ $chatMessage->type->name() }}</td>
                        <td>{{ $chatMessage->message }}</td>
                        <td>{{ $chatMessage->server }}</td>
                        <td>{{ $chatMessage->time }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $chatmessages->links() }}
            </div>
        </div>
    </div>
    {{--<div class="p-4">
        <button type="button" class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#addCommandBlockerModal"
                wire:click="addCommandBlocker">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add CommandBlocker
        </button>
    </div>--}}
</div>
