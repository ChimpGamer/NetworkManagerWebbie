<div>
    @include('livewire.filter.filter-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session()->has('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header text-center py-3">
            <h5 class="mb-0 text-center">
                <strong>Filter</strong>
            </h5>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Word</th>
                    <th>Replacement</th>
                    <th>Server</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($filters as $filter)
                    <tr>
                        <td>{{ $filter->id }}</td>
                        <td>{{ $filter->word }}</td>
                        <td>{{ $filter->replacement }}</td>
                        <td>{{ $filter->server }}</td>
                        <td>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal" data-mdb-target="#editFilterModal" wire:click="editFilter({{$filter->id}})">
                                <i class="material-icons text-warning">edit</i>
                            </button>
                            <button type="button" style="background: transparent; border: none;" data-mdb-toggle="modal" data-mdb-target="#deleteFilterModal" wire:click="deleteFilter({{$filter->id}})">
                                <i class="material-icons text-danger">delete</i>
                            </button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $filters->links() }}
            </div>
        </div>
    </div>
    <div class="p-4">
        <button type="button" class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#addFilterModal"
                wire:click="addFilter">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Filter
        </button>
    </div>
</div>
