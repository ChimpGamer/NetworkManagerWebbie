<div data-mdb-modal-init>
    @include('livewire.helpop.helpop-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="HelpOP">
        <livewire:help-o-p-table/>
    </x-card-table>
</div>
