<div data-mdb-modal-init>
    @include('livewire.chatlogs.chatlog-modals')

    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Chat Logs">
        <livewire:chat-log.chat-logs-table/>
    </x-card-table>
</div>
