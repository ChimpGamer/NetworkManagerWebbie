<div>
    @include('livewire.filter.filter-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Filters">
        <livewire:filters-table/>
    </x-card-table>
    @can('edit_filter')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addFilterModal"
                    wire:click="addFilter">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Filter
            </button>
        </div>
    @endcan
</div>
