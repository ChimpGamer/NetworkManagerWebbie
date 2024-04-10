<div class="col-12">
    <div class="card">
        <div class="card-header py-3">
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
                    @forelse ($punishments as $punishment)
                        <tr>
                            <td>{{$punishment->type->name()}}</td>
                            <td>{{$punishment->getPunisherName()}}</td>
                            <td>{{$punishment->reason}}</td>
                            <td>{{$punishment->getTimeFormatted()}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Sorry - No Data Found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $punishments->links() }}
            </div>
        </div>
    </div>
</div>
