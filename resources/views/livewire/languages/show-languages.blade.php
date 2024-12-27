<div>
    @include('livewire.languages.language-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if(session('warning-message'))
        <h5 class="alert alert-warning">{{ session('warning-message')  }}</h5>
    @endif

    <h5 class="alert alert-info"><i class="fa-solid fa-circle-info"></i> Note: These languages and language message are
        all in-game.</h5>

    <x-card-table title="Languages">
        <livewire:languages.languages-table />
    </x-card-table>
    <div class="p-4">
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addLanguageModal"
                wire:click="addLanguage">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Language
        </button>
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addLanguageMessageModal"
                wire:click="addLanguage">
            <i style="font-size: 18px !important;" class="material-icons">add</i> Add Language Message
        </button>
    </div>
</div>
