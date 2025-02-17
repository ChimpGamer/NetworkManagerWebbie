<!-- Add AccountGroup Modal -->
<div wire:ignore.self class="modal fade" id="addAccountGroupModal" tabindex="-1"
     aria-labelledby="addAccountGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAccountGroupModalLabel">@lang('accounts.account-groups.modal.add.title')</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createAccountGroup'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">@lang('accounts.account-groups.modal.add.name-label')</label>
                        <input type="text" wire:model="groupname" class="form-control">
                        @error('groupname') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">@lang('accounts.account-groups.modal.close')
                    </button>
                    <button type="submit" class="btn btn-primary">@lang('accounts.account-groups.modal.add.submit-button')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit AccountGroup Modal -->
<div wire:ignore.self class="modal fade" id="editAccountGroupModal" tabindex="-1"
     aria-labelledby="editAccountGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAccountGroupModalLabel">@lang('accounts.account-groups.modal.edit.title')</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updateAccountGroup'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">@lang('accounts.account-groups.modal.edit.name-label')</label>
                        <input type="text" wire:model="groupname" class="form-control">
                        @error('groupname') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">@lang('accounts.account-groups.modal.edit.permissions-label')</label>
                        @foreach($permissions as $key => $value)
                            <div class="form-check" wire:key="{{$key}}">
                                <input class="form-check-input" type="checkbox" wire:model="permissions.{{$key}}"
                                       id="permission-{{$key}}">
                                <label class="form-check-label" for="permission-{{$key}}">{{$key}}</label>
                                @error('permissions.' . $key) <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">@lang('accounts.account-groups.modal.close')
                    </button>
                    <button type="submit" class="btn btn-primary">@lang('accounts.account-groups.modal.edit.submit-button')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete AccountGroup Modal -->
<div wire:ignore.self class="modal fade" id="deleteAccountGroupModal" tabindex="-1"
     aria-labelledby="deleteAccountGroupModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountGroupModalLabel">@lang('accounts.account-groups.modal.delete.title')</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>@lang('accounts.account-groups.modal.delete.text', ['groupName' => $groupname])</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">@lang('accounts.account-groups.modal.close')
                </button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal"
                        data-mdb-dismiss="modal">@lang('accounts.account-groups.modal.edit.submit-button')
                </button>
            </div>
        </div>
    </div>
</div>
