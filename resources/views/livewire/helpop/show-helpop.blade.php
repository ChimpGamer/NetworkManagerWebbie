<div>
    @include('livewire.helpop.helpop-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session()->has('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header text-center py-3">
            <h5 class="mb-0 text-center">
                <strong>HelpOP</strong>
            </h5>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Requester</th>
                    <th>Message</th>
                    <th>Server</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td><a href="/players/{{ $item->requester }}"><img src="https://crafatar.com/avatars/{{ $item->requester }}?size=20" alt="requester avatar"> {{ $item->username }}</a></td>
                        <td>{{ $item->message }}</td>
                        <td>{{ $item->server }}</td>
                        <td>{{ $item->time }}</td>
                        <td>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal"
                                    data-mdb-target="#deleteHelpOPModal" wire:click="deleteHelpOP({{$item->id}})">
                                <i class="material-icons text-danger">delete</i>
                            </button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>