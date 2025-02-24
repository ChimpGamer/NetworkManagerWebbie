<div>

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <x-card-table title="{{ __('player.players.title') }}">
        <livewire:player.players-table/>
    </x-card-table>
</div>
