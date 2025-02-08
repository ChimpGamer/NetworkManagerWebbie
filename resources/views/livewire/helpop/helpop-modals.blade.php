<!-- Delete HelpOP Modal -->
<x-modal id="deleteHelpOPModal" title="Delete HelpOP Confirm">
    <p>Are you sure you want to delete Help OP #{{ $helpOPId }}?</p>
    <x-slot name="footer">
        <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
        <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">
            Yes, Delete
        </button>
    </x-slot>
</x-modal>
