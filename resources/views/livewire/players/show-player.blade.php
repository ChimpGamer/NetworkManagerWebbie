<div>
    <!-- Delete Player Modal -->
    <div wire:ignore.self class="modal fade" id="deletePlayerModal" tabindex="-1" aria-labelledby="deletePlayerModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePlayerModalLabel">Delete Player Confirm</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete ALL data of player the {{ $player->username }} ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                    <button type="button" wire:click.prevent="deletePlayer()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

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
            @can('punish_player')
                <button class="btn btn-warning btn-floating" x-data x-tooltip.raw="Punish player"
                        data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#punishPlayerModal"
                        wire:click="punishPlayer"><i class="material-icons md-18">gavel</i></button>
            @endcan
            @can('delete_player')
                <button class="btn btn-danger btn-floating" data-mdb-modal-init data-mdb-target="#deletePlayerModal" x-data x-tooltip.raw="Delete ALL player data!"><i class="material-icons md-18">delete</i></button>
            @endcan
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
                                <td>{{$player->tagNames()}}</td>
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
                                        @php
                                            if ($player->online) {
                                                $online = '<i class="fas fa-check-circle fa-lg" style="color:green"></i>';
                                            } else {
                                                $online = '<i class="fas fa-xmark-circle fa-lg" style="color:red"></i>';
                                            }
                                        @endphp
                                        <a href="/players/{{$alt->uuid}}" x-data x-tooltip="{
                                        content: 'NickName: {{$alt->nickname}}<br>Version: {{$alt->version->name()}}@can('show_ip')<br>IP: {{$alt->ip}} @endcan<br>First Login: {{$player->getTimestampFormatted($alt->firstlogin)}}<br>Last Login: {{$player->getTimestampFormatted($alt->lastlogin)}}<br>Last Logout: {{$player->getTimestampFormatted($alt->lastlogout)}}<br>Online: {{$online}}',
                                        allowHTML: true,
                                        appendTo: $root
                                        }">{{$alt->username}}</a>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap">Ping</th>
                                <td>{{$player->getPingDataAsString()}}</td>
                            </tr>
                            <tr>
                                <th colspan="2">
                                    <div x-init="loadVersionsChart" id="mostUsedVersionsChart"></div>
                                </th>
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

        <!-- Player Friends List -->
        <div class="col-6">
            <x-card-table title="{{ __('player.player.friends.title') }}">
                @livewire('player.player-friends-table', ['player' => $player, 'lazy' => true])
            </x-card-table>
        </div>
    </div>
</div>

@assets
<script src="https://code.highcharts.com/stock/highstock.js"></script>
@endassets

@script
<script>
    Highcharts.setOptions({
        chart: {
            style: {
                fontFamily: 'Roboto'
            }
        }
    });
    let data = @js($player->getMostUsedVersions());
    let mdbTheme = document.documentElement.dataset.mdbTheme

    window.loadVersionsChart = () => {
        Highcharts.chart('mostUsedVersionsChart', {
            chart: {
                backgroundColor: 'transparent',
                //plotBackgroundColor: null,
                //plotBorderWidth: null,
                //plotShadow: false,
                type: 'pie',
                //height: 407
            },
            title: {
                text: 'Most used Versions',
                style: {
                    color: mdbTheme === 'dark' ? 'white' : 'black'
                }
            },
            tooltip: {
                pointFormat: '<b>{point.percentage:.1f}%</b>',
                backgroundColor: '#FFFFFF',
                borderColor: '#FFFFFF',
                borderRadius: 2,
                borderWidth: 1,
                style: {
                    fontSize: '0.9em'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: false,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        distance: 20,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: mdbTheme === 'dark' ? 'white' : 'black',
                            fontSize: '0.8em'
                        }
                    },
                },
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Versions',
                colorByPoint: true,
                data: data
            }]
        });
    }
</script>
@endscript
