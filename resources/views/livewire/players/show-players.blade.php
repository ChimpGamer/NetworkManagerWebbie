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
                        <strong>Players</strong>
                    </h5>
                </div>
                <div class="col-md-auto ms-auto" wire:ignore>
                    <div class="form-outline w-auto d-inline-block" data-mdb-input-init>
                        <input type="search" id="playerSearch" class="form-control form-control-sm" wire:model.live="search"/>
                        <label class="form-label" for="playerSearch" style="font-family: Roboto, 'FontAwesome'">Search...</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="playersTable" class="table text-center">
                <thead>
                <tr>
                    <th>Player</th>
                    <th>First login</th>
                    <th>Last login</th>
                    <th>Online</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($players as $player)
                    <tr>
                        <td><x-player-link :uuid="$player->uuid" :username="$player->username" /></td>
                        <td>{{ $player->getTimestampFormatted($player->firstlogin) }}</td>
                        <td>{{ $player->getTimestampFormatted($player->lastlogin) }}</td>
                        <td>@if ($player->online)
                                <i class="fas fa-check-circle fa-lg" style="color:green"></i>
                            @else
                                <i class="fas fa-xmark-circle fa-lg" style="color:red"></i>
                            @endif</td>
                        <th>
                            <a type="button" style="background: transparent; border: none;" href="{{ route('players.show', $player->uuid) }}">
                                <i class="material-icons text-info">info</i>
                            </a>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $players->links() }}
        </div>
    </div>

</div>
