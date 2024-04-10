<div class="col-12">
    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0 text-center">
                <strong>Player Sessions</strong>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>Started</th>
                        <th>Ended</th>
                        <th>Time</th>
                        @can('show_ip')
                            <th>IP Address</th>
                        @endcan
                        <th>Version</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($sessions as $session)
                        <tr>
                            <td>{{$session->formatStart()}}</td>
                            <td>{{$session->formatEnd()}}</td>
                            <td>{{$session->formatTime()}}</td>
                            @can('show_ip')
                                <td>{{$session->ip}}</td>
                            @endcan
                            <td>{{$session->version->name()}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Sorry - No Data Found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $sessions->links() }}
            </div>
        </div>
    </div>
</div>
