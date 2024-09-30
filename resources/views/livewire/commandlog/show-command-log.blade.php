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
                        <strong>Command Log</strong>
                    </h5>
                </div>
                <div class="col-md-auto ms-auto" wire:ignore>
                    <div class="form-outline w-auto d-inline-block" data-mdb-input-init>
                        <input type="search" id="commandLogSearch" class="form-control form-control-sm"
                               wire:model.live="search"/>
                        <label class="form-label" for="commandLogSearch" style="font-family: Roboto, 'FontAwesome'">Search...</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Command</th>
                    <th>Server</th>
                    <th>Time</th>
                </tr>
                </thead>
                <tbody>
                @forelse($executedCommands as $executedCommand)
                    <tr>
                        <td>
                            <x-player-link :uuid="$executedCommand->uuid" :username="$executedCommand->username"/>
                        </td>
                        <td>{{ $executedCommand->command }}</td>
                        <td>{{ $executedCommand->server }}</td>
                        <td>{{ $executedCommand->time }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
            {{ $executedCommands->links() }}
        </div>
    </div>
</div>
