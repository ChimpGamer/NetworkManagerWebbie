<div>
    @include('livewire.languages.language-message-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if(session('warning-message'))
        <h5 class="alert alert-warning">{{ session('warning-message')  }}</h5>
    @endif
    {{--<h5 class="alert alert-info"><i class="fa-solid fa-circle-info"></i> Don't forget to press Enter to save the message. </h5>--}}

    <x-card-table title="Language {{$language->name}}">
        <livewire:languages.language-messages-table :$language />
    </x-card-table>
    <div class="p-4">
        <button type="button" class="btn" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#editLanguageMessageModal">

        </button>
    </div>
</div>
