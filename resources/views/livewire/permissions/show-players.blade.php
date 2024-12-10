<div>
    @include('livewire.permissions.permission-player-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-success">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Permission Players">
        <livewire:permissions.permission-players-table/>
    </x-card-table>
</div>
