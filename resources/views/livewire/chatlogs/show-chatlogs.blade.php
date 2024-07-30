<div>
    @include('livewire.chatlogs.chatlog-modals')
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
                        <strong>ChatLogs</strong>
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
                    <th>ID</th>
                    <th>Creator</th>
                    <th>Tracked</th>
                    <th>Server</th>
                    <th>Time</th>
                    @can('edit_chatlog')
                        <th>Actions</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @forelse($chatlogs as $chatLog)
                    <tr>
                        <td><a href="/chatlogs/{{ $chatLog->uuid }}">{{ $chatLog->uuid }}</a></td>
                        <td>
                            <x-player-link :uuid="$chatLog->creator" :username="$chatLog->creator_player_username"/>
                        </td>
                        <td>
                            <x-player-link :uuid="$chatLog->tracked" :username="$chatLog->tracked_player_username"/>
                        </td>
                        <td>{{ $chatLog->server }}</td>
                        <td>{{ $chatLog->timeFormatted() }}</td>
                        @can('edit_chatlog')
                            <td>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init
                                        data-mdb-target="#deleteChatlogModal"
                                        wire:click="deleteChatlog('{{ $chatLog->uuid }}')">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
            {{ $chatlogs->links() }}
        </div>
    </div>
</div>
