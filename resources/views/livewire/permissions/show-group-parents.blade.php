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
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($parents as $parent)
                    <tr>
                        <td>{{ $parent->parentGroup->name }}</td>
                        <td>
                            {{--<button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal"
                                    data-mdb-target="#editGroupModal"
                                    wire:click="editGroup({{$group->id}})">
                                <i class="material-icons text-warning">edit</i>
                            </button>--}}
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal"
                                    data-mdb-target="#deleteGroupParentModal"
                                    wire:click="deleteGroupParent({{ $parent->id }}, {{ $parent->parentgroupid }})">
                                <i class="material-icons text-danger">delete</i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $parents->links() }}
            </div>
        </div>
    </div>
    <div class="p-4">
        <button type="button" class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#addGroupParentModal"
                wire:click="addGroupParent">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Group
        </button>
    </div>
</div>
