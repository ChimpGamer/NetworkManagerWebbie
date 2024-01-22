<!-- Show Server Group Modal -->
<div wire:ignore.self class="modal fade" id="showServerGroupModal" tabindex="-1" aria-labelledby="showServerGroupModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showServerGroupModalLabel">Show Server Group</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>ID</strong>
                    <p>{{ $groupId }}</p>
                </div>
                <div class="mb-3">
                    <strong>Name</strong>
                    <p>{{ $groupName }}</p>
                </div>
                <div class="mb-3">
                    <strong>Balance Method</strong>
                    <p>{{ $balanceMethod }}</p>
                </div>
                <div class="mb-3">
                    <strong>Servers</strong>
                    @foreach($servers as $server)
                        <p class="mb-0">{{ $server }}</p>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Server Modal -->
<div wire:ignore.self class="modal fade" id="deleteServerGroupModal" tabindex="-1" aria-labelledby="deleteServerGroupModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteServerGroupModalLabel">Delete Server Group Confirm</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the {{ $groupName }} server group?</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>
