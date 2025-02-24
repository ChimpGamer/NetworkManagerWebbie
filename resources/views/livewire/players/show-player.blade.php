<div>
    <!-- Punish PLayer Modal -->
    <x-modal id="punishPlayerModal" title="Punish Player" :hasForm="true" wire:submit.prevent="punish">
        <div class="mb-3">
            <label class="bold">Type</label>
            <select name="type" class="form-control" wire:model.change="punishment.typeId">
                @foreach($this->punishmentTypeCases as $punishmentType)
                    <option
                        value="{{$punishmentType}}">{{ $punishmentType->name() }}</option>
                @endforeach
            </select>
            @error('punishment.typeId') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3">
            <label class="bold">Punisher</label>
            <input type="text" wire:model="punishment.punisherUUID" class="form-control">
            @error('punishment.punisherUUID') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3">
            <label class="bold">Time</label>
            <input type="datetime-local" wire:model="punishment.time" class="form-control">
            @error('punishment.time') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        @if($punishment->isTemporary)
            <div class="mb-3">
                <label class="bold">End</label>
                <input type="datetime-local" wire:model="punishment.end" class="form-control">
                @error('punishment.end') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        @endif
        <div class="mb-3">
            <label class="bold">Reason</label>
            <input type="text" wire:model="punishment.reason" class="form-control">
            @error('punishment.reason') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        @if(!$punishment->isGlobal)
            <div class="mb-3">
                <label class="bold">Server</label>
                <input type="text" wire:model=punishment."server" class="form-control">
                @error('punishment.server') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        @endif
        <div class="mb-3">
            <label class="bold">Silent</label>
            <div class="d-flex">
                <strong>Off</strong>
                <div class="form-check form-switch ms-2">
                    <input class="form-check-input" type="checkbox" role="switch" id="silentSwitch"
                           wire:model="punishment.silent" />
                    <label class="form-check-label" style="font-weight: bold;"
                           for="silentSwitch"><strong>On</strong></label>
                </div>
                @error('punishment.silent') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="bold">Active</label>
            <div class="d-flex">
                <strong>Off</strong>
                <div class="form-check form-switch ms-2">
                    <input class="form-check-input" type="checkbox" role="switch"
                           id="activeSwitch"
                           wire:model="punishment.active" />
                    <label class="form-check-label" style="font-weight: bold;"
                           for="activeSwitch"><strong>On</strong></label>
                </div>
                @error('punishment.active') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <x-slot name="footer">
            <button type="button" class="btn btn-secondary" wire:click="closeModal"
                    data-mdb-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">PUNISH</button>
        </x-slot>
    </x-modal>

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="d-flex mb-2">
        <div class="me-auto">

        </div>
        <div>
            <button class="btn btn-warning btn-floating" x-data x-tooltip.raw="Punish player"
                    data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#punishPlayerModal"
            wire:click="punishPlayer"><i class="material-icons md-18">gavel</i></button>
            <button class="btn btn-danger btn-floating" wire:click="deletePlayer" x-data x-tooltip.raw="Delete ALL player data!"><i class="material-icons md-18">delete</i></button>
        </div>
    </div>

    <div class="row gy-4">
        <!-- Player Information -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center py-3">
                    <h5 class="mb-0 text-center">
                        <strong>@lang('player.player.information.title')</strong>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap">
                            <tbody>
                            <tr>
                                <th scope="row">@lang('player.player.information.username')</th>
                                <td>{{$player->username}}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('player.player.information.nickname')</th>
                                <td>{{$player->nickname}}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('player.player.information.uuid')</th>
                                <td>{{$player->uuid}}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('player.player.information.latest-minecraft-version')</th>
                                <td>{{$player->version->name()}}</td>
                            </tr>
                            @can('show_ip')
                                <tr>
                                    <th scope="row">@lang('player.player.information.ip-address')</th>
                                    <td>{{$player->ip}}</td>
                                </tr>
                            @endcan
                            <tr>
                                <th scope="row">@lang('player.player.information.country')</th>
                                <td>{{$player->fullCountry()}}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('player.player.information.tag')</th>
                                <td>{{$player->tag?->name}}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('player.player.information.first-login')</th>
                                <td>{{$player->getTimestampFormatted($player->firstlogin)}}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('player.player.information.last-login')</th>
                                <td>{{$player->getTimestampFormatted($player->lastlogin)}}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('player.player.information.last-logout')</th>
                                <td>{{$player->getTimestampFormatted($player->lastlogout)}}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('player.player.information.playtime')</th>
                                <td>{{$player->playtime}}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('player.player.information.online')</th>
                                <td>@if ($player->online)
                                        <i class="fas fa-check-circle fa-lg" style="color:green"></i>
                                    @else
                                        <i class="fas fa-xmark-circle fa-lg" style="color:red"></i>
                                    @endif</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Player Statistics -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center py-3">
                    <h5 class="mb-0 text-center">
                        <strong>@lang('player.player.statistics.title')</strong>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th scope="row" class="text-nowrap">@lang('player.player.statistics.average-playtime')</th>
                                <td>{{$player->getAveragePlaytime()}}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap">@lang('player.player.statistics.normally-joins-at')</th>
                                <td>{{$player->getAverageDailyLogin()}}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap">@lang('player.player.statistics.additional-accounts')</th>
                                <td>
                                    @foreach($player->getAltAccounts() as $alt)
                                        <a href="/players/{{$alt->uuid}}">{{$alt->username}}</a>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td style="text-align:center; vertical-align:middle">
                                    <canvas x-init="loadVersionsChart" id="mostUsedVersionsChart" style="position: relative; right: 20%"></canvas>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Player Sessions -->
        <div class="col-12">
            <x-card-table title="{{ __('player.player.sessions.title') }}">
                @livewire('player.player-sessions-table', ['player' => $player, 'lazy' => true])
            </x-card-table>
        </div>

        <!-- Player Punishments -->
        <div class="col-12">
            <x-card-table title="{{ __('player.player.punishments.title') }}">
                @livewire('player.player-punishments-table', ['player' => $player, 'lazy' => true])
            </x-card-table>
        </div>

        <!-- Player Ignored List -->
        <div class="col-6">
            <x-card-table title="{{ __('player.player.ignored-players.title') }}">
                @livewire('player.ignored-players-table', ['player' => $player, 'lazy' => true])
            </x-card-table>
        </div>
    </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
@endassets

@script
<script>
    window.loadVersionsChart = () => {
        // Your JS here.
        const ctx = document.getElementById('mostUsedVersionsChart');

        let data = @js($player->getMostUsedVersions());

        let labels = data['labels'];
        let values = data['values'];

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values
                }]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let value = context.formattedValue;

                                let sum = 0;
                                let dataArr = context.chart.data.datasets[0].data;
                                dataArr.map(data => {
                                    sum += Number(data);
                                });

                                return (value * 100 / sum).toFixed(2) + '%';
                            }
                        }
                    },
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Most Used Versions'
                    }
                }
            }
        });
    }
</script>
@endscript
