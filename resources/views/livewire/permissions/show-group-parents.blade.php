<div>
    @include('livewire.permissions.permission-group-parents-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if(session()->has('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header h5">
            Parents of {{ $group->name }}
        </div>
        <div class="card-body border-0 shadow table-responsive">
            <table class="table text-center">
                <thead>
                <tr>
                    <th>Parent Group</th>
                    @can('edit_permissions')
                        <th>Actions</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @foreach($parents as $parent)
                    <tr>
                        <td>{{ $parent->parentGroup->name }}</td>
                        @can('edit_permissions')
                            <td>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init
                                        data-mdb-target="#deleteGroupParentModal"
                                        wire:click="deleteGroupParent({{ $parent->id }}, {{ $parent->parentgroupid }})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $parents->links() }}
        </div>
    </div>
    @can('edit_permissions')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addGroupParentModal"
                    wire:click="addGroupParent">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Group
            </button>
        </div>
    @endcan
</div>
