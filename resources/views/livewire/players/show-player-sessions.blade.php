<div class="col-12">
    <div class="card">
        <div class="card-header text-center py-3">
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
                        <th>IP Address</th>
                        <th>Version</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($sessions as $session)
                        <tr>
                            <td>{{$session->start}}</td>
                            <td>{{$session->end}}</td>
                            <td>{{$session->time}}</td>
                            <td>{{$session->ip}}</td>
                            <td>{{$session->version}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $sessions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
