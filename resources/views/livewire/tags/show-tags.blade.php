<div>
    @include('livewire.tags.tags-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Tags">
        <livewire:tags.tags-table />
    </x-card-table>
    @can('edit_tags')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                    data-mdb-target="#addTagModal"
                    wire:click="addTag">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Tag
            </button>
        </div>
    @endcan
</div>
