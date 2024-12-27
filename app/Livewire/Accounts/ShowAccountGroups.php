<?php

namespace App\Livewire\Accounts;

use App\Models\Group;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowAccountGroups extends Component
{
    use AuthorizesRequests;

    public int $group_id = -1;

    public string $groupname = '';

    public array $permissions = [];

    protected function rules()
    {
        return [
            'groupname' => 'required|string',
        ];
    }

    protected array $editRules = [
        'groupname' => 'required|string',
        'permissions' => 'required|array',
        'permissions.*' => 'required|boolean',
    ];

    public function addAccountGroup(): void
    {
        $this->authorize('manage_groups_and_accounts');
        $this->resetInput();
    }

    public function createAccountGroup(): void
    {
        $this->authorize('manage_groups_and_accounts');
        $validatedData = $this->validate();
        $groupname = $validatedData['groupname'];

        Group::create([
            'name' => $groupname,
        ]);

        session()->flash('message', 'Successfully Added Account Group');
        $this->closeModal('addAccountGroupModal');
    }

    #[On('edit-group')]
    public function editAccountGroup($rowId): void
    {
        $this->authorize('manage_groups_and_accounts');
        $this->resetInput();
        $group = Group::find($rowId);
        if ($group == null) {
            session()->flash('error', 'Account Group $'.$rowId.' not found');

            return;
        }

        $this->group_id = $group->id;
        $this->groupname = $group->name;

        $permissions = array_slice($group->attributesToArray(), 2);
        $this->permissions = $permissions;
    }

    public function updateAccountGroup(): void
    {
        $this->authorize('manage_groups_and_accounts');
        $validateData = $this->validate($this->editRules);
        $permissions = $validateData['permissions'];

        $update = array_merge(['name' => $validateData['groupname']], $permissions);
        //dd($update);
        $group = Group::find($this->group_id);
        $group->users->each(function ($user) use ($validateData) {
            $user->update([
                'usergroup' => $validateData['groupname'],
            ]);
        });

        $group->update($update);

        $this->refreshTable();
        $this->refreshAccountsTable();
        session()->flash('message', 'Account Group Updated Successfully');
        $this->closeModal('editAccountGroupModal');
    }

    #[On('delete-group')]
    public function deleteAccountGroup($rowId): void
    {
        $this->authorize('manage_groups_and_accounts');
        $group = Group::find($rowId);
        if ($group == null) {
            session()->flash('error', 'Account Group $'.$rowId.' not found');

            return;
        }

        if ($group->users->isNotEmpty()) {
            session()->flash('error', 'Account Group '.$group->name.' has users. Change the users to a different group before deleting this group!');

            usleep(50000);
            $this->closeModal('deleteAccountGroupModal');
            return;
        }

        $this->group_id = $group->id;
        $this->groupname = $group->name;
    }

    public function delete(): void
    {
        $this->authorize('manage_groups_and_accounts');
        Group::find($this->group_id)->delete();

        $this->resetInput();
        $this->refreshTable();
    }

    public function closeModal(?string $modalId = null): void
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    private function resetInput(): void
    {
        $this->reset(
            'group_id',
            'groupname'
        );
    }

    private function refreshAccountsTable(): void
    {
        $this->dispatch('pg:eventRefresh-accounts-table');
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-account-groups-table');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.accounts.show-account-groups');
    }
}
