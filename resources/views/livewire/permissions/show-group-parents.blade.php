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
    @can('edit_permissions')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addGroupParentModal"
                    wire:click="addGroupParent">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Group
            </button>
        </div>
    @endcan
</div>
