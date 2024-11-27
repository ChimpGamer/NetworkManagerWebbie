<div>
    @include('livewire.punishment_templates.punishment-template-modals')

    @if (session('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <x-card-table title="Punishment Templates">
        <livewire:punishment-templates.punishment-templates-table />
    </x-card-table>
    @can('edit_pre_punishments')
        <div class="p-4">
            <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addTemplateModal"
                    wire:click="addTemplate">
                <i style="font-size: 18px !important;" class="material-icons">add</i> Add Template
            </button>
        </div>
    @endcan
</div>
