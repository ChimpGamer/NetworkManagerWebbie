<div>
    @include('livewire.permissions.permission-group-prefixes-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <div class="card">
        <div class="card-header h5">
            Prefixes of {{ $group->name }}
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table class="table text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Prefix</th>
                    <th>Server</th>
                    @can('edit_permissions')
                        <th>Actions</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach($prefixes as $prefix)
                    @php
                        $server = $prefix->server;
                        if (empty($server)) {
                            $server = "ALL";
                        }
                    @endphp
                    <tr>
                        <td>{{ $prefix->id }}</td>
                        <td>{{ $prefix->prefix }}</td>
                        <td>{{ $server }}</td>
                        @can('edit_permissions')
                            <td>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init
                                        data-mdb-target="#editGroupPrefixModal"
                                        wire:click="editGroupPrefix({{$prefix->id}})">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init
                                        data-mdb-target="#deleteGroupPrefixModal"
                                        wire:click="deleteGroupPrefix({{ $prefix->id }})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $prefixes->links() }}
        </div>
    </div>
    @can('edit_permissions')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addGroupPrefixModal"
                    wire:click="addGroupPrefix">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Prefix
            </button>
        </div>
    @endcan
</div>
