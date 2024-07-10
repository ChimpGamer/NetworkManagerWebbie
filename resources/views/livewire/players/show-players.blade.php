<div>

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0 text-center">
                <strong>Players</strong>
            </h5>

            <div class="float-end d-inline" wire:ignore>
                <div class="form-outline" data-mdb-input-init>
                    <input type="search" id="playerSearch" class="form-control" wire:model.live="search"/>
                    <label class="form-label" for="playerSearch"
                           style="font-family: Roboto, 'FontAwesome'">Search...</label>
                </div>
            </div>
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table id="playersTable" class="table text-center">
                <thead>
                <tr>
                    <th>Player</th>
                    <th>FirstLogin</th>
                    <th>Online</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($players as $player)
                    <tr>
                        <td><img alt="player avatar" src="https://minotar.net/helm/{{ $player->uuid  }}/20" loading="lazy"> {{$player->username}}</td>
                        <td>{{ $player->getTimestampFormatted($player->firstlogin) }}</td>
                        <td>@if ($player->online)
                                <i class="fas fa-check-circle fa-lg" style="color:green"></i>
                            @else
                                <i class="fas fa-xmark-circle fa-lg" style="color:red"></i>
                            @endif</td>
                        <th>
                            <a type="button" style="background: transparent; border: none;" href="/players/{{$player->uuid}}">
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
