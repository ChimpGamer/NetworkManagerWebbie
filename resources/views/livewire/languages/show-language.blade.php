<div>
    <h5 class="alert alert-info"><i class="fa-solid fa-circle-info"></i> Don't forget to press Enter to save the message. </h5>

    <x-card-table title="Language {{$language->name}}">
        <livewire:languages.language-messages-table :$language />
    </x-card-table>
</div>
