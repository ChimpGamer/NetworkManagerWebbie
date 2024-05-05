<div x-data @close-modal.window=""">
    @include('ultimatetags::livewire.tags-modals')

    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session()->has('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0 text-center">
                <strong>Tags</strong>
            </h5>

            <div class="float-end d-inline" wire:ignore>
                <div class="form-outline" data-mdb-input-init>
                    <input type="search" id="tagsSearch" class="form-control" wire:model.live="search"/>
                    <label class="form-label" for="tagsSearch"
                           style="font-family: Roboto, 'FontAwesome'">Search...</label>
                </div>
            </div>
        </div>

        <div class="card-body border-0 shadow table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Tag</th>
                    <th>Description</th>
                    <th>Permission</th>
                    <th>Server</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tags as $tag)
                    <tr>
                        <td>{{ $tag->id }}</td>
                        <td>{{ $tag->name }}</td>
                        <td>{!! $tag->tag !!}</td>
                        <td>{{ $tag->description }}</td>
                        <td>{{ $tag->permission }}</td>
                        <td>{{ $tag->server }}</td>
                        <td>
                            <button type="button" style="background: transparent; border: none;"
                                    data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#editTagModal" wire:click="editTag({{$tag->id}})">
                                <i class="material-icons text-warning">edit</i>
                            </button>
                            <button type="button" style="background: transparent; border: none;"
                                    data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#deleteTagModal" wire:click="deleteTag({{$tag->id}})">
                                <i class="material-icons text-danger">delete</i>
                            </button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            {{ $tags->links() }}
        </div>
    </div>
    <div class="p-4">
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                data-mdb-target="#addTagModal"
                wire:click="addTag">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Tag
        </button>
    </div>
</div>
