<div>
    <section class="mb-4">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Online Players</strong>
                </h5>
            </div>
            <div class="card-body">
                @livewire('analytics.online-players-chart', ['lazy' => true])
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Most Played Versions</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>Version</th>
                            <th>Players</th>
                            <th>Percentage</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($this->mostPlayedVersions as $mostPlayedVersion)
                            <tr>
                                <td>{{ $mostPlayedVersion['version'] }}</td>
                                <td>{{ $mostPlayedVersion['players'] }}</td>
                                <td>{{ $mostPlayedVersion['percentage'] }}%</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Most Used Virtual Hosts</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>Virtual Host</th>
                            <th>Players</th>
                            <th>Population</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($this->mostUsedVirtualHosts as $mostUsedVirtualHost)
                            <tr>
                                <td>{{ $mostUsedVirtualHost['vhost'] }}</td>
                                <td>{{ $mostUsedVirtualHost['players'] }}</td>
                                <td>{{ $mostUsedVirtualHost['percentage'] }}%</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
