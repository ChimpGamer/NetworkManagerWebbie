<div>
    <div class="card">
        <div class="card-header">
            <div class="row mt-2 justify-content-between text-center">
                <div class="col-md-auto me-auto">
                    <label>Show
                        <select class="form-select form-select-sm" style="display: inherit; width: auto"
                                wire:model.live="per_page">
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
                        <strong>ChatLog {{ $chatLog->uuid }}</strong>
                        <br />
                        <strong>Created by {{ $chatLog->creatorName() }}</strong>
                    </h5>
                </div>
                <div class="col-md-auto ms-auto" wire:ignore>
                    <div class="form-outline w-auto d-inline-block" data-mdb-input-init>
                        <input type="search" id="chatSearch" class="form-control form-control-sm"
                               wire:model.live="search"/>
                        <label class="form-label" for="chatSearch"
                               style="font-family: Roboto, 'FontAwesome'">Search...</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Player</th>
                    <th>Server</th>
                    <th>Time</th>
                </tr>
                </thead>
                <tbody>
                @forelse($chatMessages as $chatMessage)
                    <tr>
                        <td>
                            <x-player-link :uuid="$chatMessage->uuid" :username="$chatMessage->username"/>
                        </td>
                        <td>{{ $chatMessage->server }}</td>
                        <td>{{ $chatMessage->time }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
            {{ $chatMessages->links() }}
        </div>
    </div>
</div>
