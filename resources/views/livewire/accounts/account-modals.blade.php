<!-- Add Account Modal -->
<div wire:ignore.self class="modal fade" id="addAccountModal" tabindex="-1"
     aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAccountModalLabel">@lang('accounts.accounts.modal.add.title')</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='createAccount'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">@lang('accounts.accounts.modal.add.username-label')</label>
                        <input type="text" wire:model="username" class="form-control">
                        @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('accounts.accounts.modal.add.password-label')</label>
                        <input type="password" wire:model="password" name="password" class="form-control">
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('accounts.accounts.modal.add.confirm-password-label')</label>
                        <input type="password" wire:model="password_confirmation" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('accounts.accounts.modal.add.group-label')</label>
                        <select class="form-control" wire:model="user_group">
                            <option selected>Select a group...</option>

                            @foreach ($this->allUserGroups as $group)
                                <option value="{{ $group->name }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        @error('user_group') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">@lang('accounts.accounts.modal.close')
                    </button>
                    <button type="submit" class="btn btn-primary">@lang('accounts.accounts.modal.add.submit-button')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Account Modal -->
<div wire:ignore.self class="modal fade" id="editAccountModal" tabindex="-1"
     aria-labelledby="editAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAccountModalLabel">@lang('accounts.accounts.modal.edit.title')</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit='updateAccount'>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="bold">@lang('accounts.accounts.modal.edit.username-label')</label>
                        <input type="text" wire:model="username" class="form-control">
                        @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="bold">@lang('accounts.accounts.modal.edit.group-label')</label>
                        <select class="form-control" wire:model="user_group">
                            <option disabled>Select a group...</option>

                            @foreach ($this->allUserGroups as $group)
                                <option value="{{ $group->name }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        @error('user_group') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                            data-mdb-dismiss="modal">@lang('accounts.accounts.modal.close')
                    </button>
                    <button type="submit" class="btn btn-primary">@lang('accounts.accounts.modal.edit.submit-button')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div wire:ignore.self class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">@lang('accounts.accounts.modal.delete.title')</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>@lang('accounts.accounts.modal.delete.text', ['userName' => $username])</p>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">@lang('accounts.accounts.modal.close')</button>
                <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal" data-mdb-dismiss="modal">@lang('accounts.accounts.modal.delete.submit-button')</button>
            </div>
        </div>
    </div>
</div>
