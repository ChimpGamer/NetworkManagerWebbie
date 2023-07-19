<div class="col-12">
    <div class="card">
        <div class="card-header text-center py-3">
            <h5 class="mb-0 text-center">
                <strong>Player Punishments</strong>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>Punishment</th>
                        <th>Punisher</th>
                        <th>Reason</th>
                        <th>Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($punishments as $punishment)
                        <tr>
                            <td>{{$punishment->type->name()}}</td>
                            <td>{{$punishment->getPunisherName()}}</td>
                            <td>{{$punishment->reason}}</td>
                            <td>{{$punishment->getTimeFormatted()}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $punishments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
