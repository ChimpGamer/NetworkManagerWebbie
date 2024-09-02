<div>
    @include('ultimatejqmessages::livewire.join-quit-message-modals')

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
                        <strong>Join Quit Messages</strong>
                    </h5>
                </div>
                <div class="col-md-auto ms-auto" wire:ignore>
                    <div class="form-outline w-auto d-inline-block" data-mdb-input-init>
                        <input type="search" id="jqMessageSearch" class="form-control form-control-sm" wire:model.live="search"/>
                        <label class="form-label" for="jqMessageSearch" style="font-family: Roboto, 'FontAwesome'">Search...</label>
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
                    <th>Type</th>
                    <th>Message</th>
                    <th>Permission</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($messages as $message)
                    <tr>
                        <td>{{ $message->id }}</td>
                        <td>{{ $message->name }}</td>
                        <td>{{ $message->type->name() }}</td>
                        <td>{!! $message->message !!}</td>
                        <td>{{ $message->permission }}</td>
                        <td>
                            <button type="button" style="background: transparent; border: none;"
                                    data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#editJQMessageModal" wire:click="editJQMessage({{$message->id}})">
                                <i class="material-icons text-warning">edit</i>
                            </button>
                            <button type="button" style="background: transparent; border: none;"
                                    data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#deleteJQMessageModal" wire:click="deleteJQMessage({{$message->id}})">
                                <i class="material-icons text-danger">delete</i>
                            </button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            {{ $messages->links() }}
        </div>
    </div>
    <div class="p-4">
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                data-mdb-target="#addJQMessageModal"
                wire:click="addJQMessage">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Join Quit Message
        </button>
    </div>
</div>
