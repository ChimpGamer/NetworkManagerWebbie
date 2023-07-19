<section>
    <div class="row gy-4">
        <!-- Player Information -->
        <div class="col-6">
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
                                <td>{{$player->version}}</td>
                            </tr>
                            <tr>
                                <th scope="row">IP Address</th>
                                <td>{{$player->ip}}</td>
                            </tr>
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
        <div class="col-6">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Player Sessions -->
        <livewire:show-player-sessions :player="$player"></livewire:show-player-sessions>

        <!-- Player Punishments -->
        <livewire:show-player-punishments :player="$player"></livewire:show-player-punishments>
    </div>
</section>
