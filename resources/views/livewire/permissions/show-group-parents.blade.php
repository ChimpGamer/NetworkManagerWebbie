<div>
    @include('livewire.permissions.permission-group-parents-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if(session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Parents of {{ $group->name }}">
        <livewire:permissions.group-parents-table groupId="{{ $group->id }}" />
    </x-card-table>

    {{--<div class="card">
        <div class="card-header h5 text-center mb-0">
            <strong>Parents of {{ $group->name }}</strong>
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
                @forelse($parents as $parent)
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
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $parents->links() }}
        </div>
    </div>--}}
    @can('edit_permissions')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addGroupParentModal"
                    wire:click="addGroupParent">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Group
            </button>
        </div>
    @endcan
</div>
