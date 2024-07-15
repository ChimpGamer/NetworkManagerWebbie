<div>
    @include('livewire.filter.filter-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session()->has('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row mt-2 justify-content-between text-center">
                <div class="col-md-auto me-auto">
                    <label>Show
                        <select class="form-select form-select-sm" style="display: inherit; width: auto" wire:model.live="per_page">
                            <option value=10>10</option>
                            <option value=25>25</option>
                            <option value=50>50</option>
                            <option value=100>100</option>
                        </select>
                        entries
                    </label>
                </div>
                <div class="col-md-auto">
                    <h5 class="mb-0 text-center">
                        <strong>Filter</strong>
                    </h5>
                </div>
                <div class="col-md-auto ms-auto" wire:ignore>
                    <div class="form-outline w-auto d-inline-block" data-mdb-input-init>
                        <input type="search" id="filterSearch" class="form-control form-control-sm" wire:model.live="search"/>
                        <label class="form-label" for="filterSearch" style="font-family: Roboto, 'FontAwesome'">Search...</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Word</th>
                    <th>Replacement</th>
                    <th>Server</th>
                    <th>Actions</th>
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
                        <td>{{ $filter->name }}</td>
                        <td>{{ $filter->word }}</td>
                        <td>{{ $filter->replacement }}</td>
                        <td>{{ $filter->server }}</td>
                        <td>
                            <button type="button" style="background: transparent; border: none;" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#showFilterModal"
                                    wire:click="showFilter({{ $filter->id }})">
                                <i class="material-icons text-info">info</i>
                            </button>
                            @can('edit_filter')
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
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Sorry - No Data Found</td>
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
