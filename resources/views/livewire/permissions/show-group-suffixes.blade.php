<div>
    @include('livewire.permissions.permission-group-suffixes-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header h5">
            Suffixes of {{ $group->name }}
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table class="table text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Suffix</th>
                    <th>Server</th>
                    @can('edit_permissions')
                        <th>Actions</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach($suffixes as $suffix)
                    @php
                        $server = $suffix->server;
                        if (empty($server)) {
                            $server = "ALL";
                        }
                    @endphp
                    <tr>
                        <td>{{ $suffix->id }}</td>
                        <td>{{ $suffix->suffix }}</td>
                        <td>{{ $server }}</td>
                        @can('edit_permissions')
                            <td>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-toggle="modal"
                                        data-mdb-target="#editGroupSuffixModal"
                                        wire:click="editGroupSuffix({{$suffix->id}})">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-toggle="modal"
                                        data-mdb-target="#deleteGroupSuffixModal"
                                        wire:click="deleteGroupSuffix({{ $suffix->id }})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $suffixes->links() }}
            </div>
        </div>
    </div>
    <div class="p-4">
        <button type="button" class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#addGroupSuffixModal"
                wire:click="addGroupSuffix">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Suffix
        </button>
    </div>
</div>
