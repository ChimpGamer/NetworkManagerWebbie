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
                    @can('edit_filter')
                        <th>Actions</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @forelse($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td><a href="/players/{{ $item->requester }}"><img
                                    src="https://minotar.net/avatar/{{ $item->requester }}/20"
                                    alt="requester avatar"> {{ $item->username }}</a></td>
                        <td>{{ $item->message }}</td>
                        <td>{{ $item->server }}</td>
                        <td>{{ $item->time }}</td>
                        @can('edit_filter')
                            <td>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init
                                        data-mdb-target="#deleteHelpOPModal" wire:click="deleteHelpOP({{$item->id}})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $data->links() }}
        </div>
    </div>
</div>
