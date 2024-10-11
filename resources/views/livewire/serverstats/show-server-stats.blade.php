<div>
    <h5 class="alert alert-warning">Be aware that this part of the web interface is experimental!</h5>
    <section class="mb-4 row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center py-3">
                    <h5 class="mb-0 text-center">
                        <strong>Advanced Server Analytics</strong>
                    </h5>
                </div>
                <div class="card-body">
                    @livewire('server-stats.advanced-server-analytics', ['lazy' => true])
                </div>
            </div>
        </div>
    </section>
</div>

@section('script')
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
@endsection
