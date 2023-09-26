<div>
    <div class="row gy-4">
        <!-- Player Information -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center py-3">
                    <h5 class="mb-0 text-center">
                        <strong>Player Information</strong>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap">
                            <tbody>
                            <tr>
                                <th scope="row">Username</th>
                                <td>{{$player->username}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Nickname</th>
                                <td>{{$player->nickname}}</td>
                            </tr>
                            <tr>
                                <th scope="row">UUID</th>
                                <td>{{$player->uuid}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Country</th>
                                <td>{{$player->country}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Latest Minecraft Version</th>
                                <td>{{$player->version->name()}}</td>
                            </tr>
                            @can('show_ip')
                                <tr>
                                    <th scope="row">IP Address</th>
                                    <td>{{$player->ip}}</td>
                                </tr>
                            @endcan
                            <tr>
                                <th scope="row">Joined</th>
                                <td>{{$player->getTimestampFormatted($player->firstlogin)}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Last Login</th>
                                <td>{{$player->getTimestampFormatted($player->lastlogin)}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Last Logout</th>
                                <td>{{$player->getTimestampFormatted($player->lastlogout)}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Playtime</th>
                                <td>{{$player->playtime}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Online</th>
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
                        <strong>Player Statistics</strong>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap">
                            <tbody>
                            <tr>
                                <th scope="row">Average Playtime</th>
                                <td>{{$player->getAveragePlaytime()}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Player normally joins at</th>
                                <td>{{$player->getAverageDailyLogin()}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Additional Accounts</th>
                                <td>
                                    @foreach($player->getAltAccounts() as $alt)
                                        <a href="/players/{{$alt->uuid}}">{{$alt->username}}</a>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td style="text-align:center; vertical-align:middle">
                                    <canvas id="mostUsedVersionsChart" style="position: relative; right: 20%"></canvas>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Player Sessions -->
        @livewire('player.show-player-sessions', ['player' => $player])

        <!-- Player Punishments -->
        @livewire('player.show-player-punishments', ['player' => $player])
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

    <script>
        document.addEventListener('livewire:load', function () {
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
                                label: function(context) {
                                    let value = context.formattedValue;

                                    let sum = 0;
                                    let dataArr = context.chart.data.datasets[0].data;
                                    dataArr.map(data => {
                                        sum += Number(data);
                                    });

                                    return  (value * 100 / sum).toFixed(2) + '%';
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
        })
    </script>
</div>
