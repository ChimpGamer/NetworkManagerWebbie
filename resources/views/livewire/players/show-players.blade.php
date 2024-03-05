<div>

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header h5">
            Players
            <label for="punishmentSearch" class="float-end mx-2">
                <input id="punishmentSearch" type="search" wire:model.live="search" class="form-control"
                       placeholder="Search..." />
            </label>
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
                @foreach ($players as $player)
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
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $players->links() }}
            </div>
        </div>
    </div>

</div>
