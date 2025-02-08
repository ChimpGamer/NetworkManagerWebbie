<div>
    <section class="mb-4 row">
        <div class="col-9">
            <div class="card">
                <div class="card-header text-center py-3">
                    <h5 class="mb-0 text-center">
                        <strong>Online Players</strong>
                        <i class="material-icons" style="font-size: 20px;" x-data x-tooltip.raw.interactive.placement.bottom="GRAPH OF THE ONLINE PLAYERS FROM THE LAST 30 DAYS">help_outline</i>
                    </h5>
                </div>
                <div class="card-body">
                    @livewire('analytics.online-players-chart', ['lazy' => true])
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-header text-center py-3">
                    <h5 class="mb-0 text-center">
                        <strong>Average Stats</strong>
                        <i class="material-icons" style="font-size: 20px;" x-data x-tooltip.raw.interactive.placement.bottom="ANPPD(AVERAGE NEW PLAYERS PER DAY) AND ARPPD(AVERAGE RETURNING PLAYER PER DAY) SHOW STATS ON MONTHLY, WEEKLY AND DAILY BASIS">help_outline</i>
                    </h5>
                </div>
                <div class="card-body">
                    @livewire('analytics.average-stats', ['lazy' => true])
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Player Regions</strong>
                    <i class="material-icons" style="font-size: 20px;" x-data x-tooltip.raw.interactive.placement.bottom="SHOWS WHERE FROM WHICH COUNTRY YOUR PLAYERS COME FROM IN A BAR-CHART AND A MAP">help_outline</i>
                </h5>
            </div>
            <div class="card-body">
                @livewire('analytics.player-regions-chart', ['lazy' => true])
            </div>
        </div>
    </section>

    <section class="mb-4">
        <x-card-table title="Most Played Version">
            <livewire:analytics.most-played-versions-table />
        </x-card-table>
    </section>

    <section class="mb-4">
        <x-card-table title="Most Used Virtual Hosts">
            <livewire:analytics.most-used-virtual-hosts-table />
        </x-card-table>
    </section>
</div>

@section('script')
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
@endsection
