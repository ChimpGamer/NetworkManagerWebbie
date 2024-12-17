<div>
    @include('ultimatejqmessages::livewire.join-quit-message-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Join Quit Messages">
        @livewire('ultimatejqmessages::join-quit-messages-table')
    </x-card-table>
    <div class="p-4">
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init
                data-mdb-target="#addJQMessageModal"
                wire:click="addJQMessage">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Join Quit Message
        </button>
    </div>
</div>
