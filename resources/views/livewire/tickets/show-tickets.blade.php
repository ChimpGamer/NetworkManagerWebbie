<div>

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif

    <x-card-table title="{{ __('tickets.title') }}">
        <livewire:tickets.tickets-table/>
    </x-card-table>
</div>
