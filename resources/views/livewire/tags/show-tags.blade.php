<div>
    @include('livewire.tags.tags-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row mt-2 align-items-center text-center">
                <div class="col-md-12">
                    <h5 class="mb-0">
                        <strong>Tags</strong>
                    </h5>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <livewire:tags.tags-table />
        </div>
    </div>
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
