<div>
    @include('livewire.filter.filter-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session()->has('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0 text-center">
                <strong>Filter</strong>
            </h5>

            <div class="float-end d-inline" wire:ignore>
                <div class="form-outline" data-mdb-input-init>
                    <input type="search" id="filterSearch" class="form-control" wire:model.live="search"/>
                    <label class="form-label" for="filterSearch"
                           style="font-family: Roboto, 'FontAwesome'">Search...</label>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Word</th>
                    <th>Replacement</th>
                    <th>Server</th>
                    @can('edit_filter')
                        <th>Actions</th>
                    @endcan
                </tr>
                </thead>
                <tbody>
                @forelse($filters as $filter)
                    <tr>
                        <td>@if ($filter->enabled)
                                <i class="fas fa-check-circle fa-lg text-success"></i>
                            @else
                                <i class="fas fa-exclamation-circle fa-lg text-danger"></i>
                            @endif {{ $filter->id }}</td>
                        <td>{{ $filter->word }}</td>
                        <td>{{ $filter->replacement }}</td>
                        <td>{{ $filter->server }}</td>
                        @can('edit_filter')
                            <td>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init
                                        data-mdb-target="#editFilterModal" wire:click="editFilter({{$filter->id}})">
                                    <i class="material-icons text-warning">edit</i>
                                </button>
                                <button type="button" style="background: transparent; border: none;"
                                        data-mdb-ripple-init data-mdb-modal-init
                                        data-mdb-target="#deleteFilterModal" wire:click="deleteFilter({{$filter->id}})">
                                    <i class="material-icons text-danger">delete</i>
                                </button>
                            </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Sorry - No Data Found</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
            {{ $filters->links() }}
        </div>
    </div>
    @can('edit_filter')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addFilterModal"
                    wire:click="addFilter">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Filter
            </button>
        </div>
    @endcan
</div>
