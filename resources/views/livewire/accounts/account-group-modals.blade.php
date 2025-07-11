<!-- Add AccountGroup Modal -->
<x-modal id="addAccountGroupModal" title="{{ __('accounts.account-groups.modal.add.title') }}" :hasForm="true" wire:submit.prevent="createAccountGroup">
    <div class="mb-3">
        <label class="bold">@lang('accounts.account-groups.modal.add.name-label')</label>
        <input type="text" wire:model="groupname" class="form-control">
        @error('groupname') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">@lang('accounts.account-groups.modal.close')</button>
        <button type="submit" class="btn btn-primary">Add</button>
    </x-slot>
</x-modal>

<!-- Edit AccountGroup Modal -->
<x-modal id="editAccountGroupModal" title="{{ __('accounts.account-groups.modal.edit.title') }}" :hasForm="true" wire:submit.prevent="updateAccountGroup">
    <div class="mb-3">
        <label class="bold">@lang('accounts.account-groups.modal.edit.name-label')</label>
        <input type="text" wire:model="groupname" class="form-control">
        @error('groupname') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
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

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                data-mdb-dismiss="modal">@lang('accounts.account-groups.modal.close')</button>
        <button type="submit" class="btn btn-primary">@lang('accounts.account-groups.modal.edit.submit-button')</button>
    </x-slot>
</x-modal>

<!-- Delete AccountGroup Modal -->
<x-modal id="deleteAccountGroupModal" title="{{ __('accounts.account-groups.modal.delete.title') }}">
    <p>@lang('accounts.account-groups.modal.delete.text', ['groupName' => $groupname])</p>
    <x-slot name="footer">
        <button type="button" wire:click="closeModal" class="btn btn-secondary" data-mdb-dismiss="modal">@lang('accounts.account-groups.modal.close')
        </button>
        <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal"
                data-mdb-dismiss="modal">@lang('accounts.account-groups.modal.edit.submit-button')
        </button>
    </x-slot>
</x-modal>
