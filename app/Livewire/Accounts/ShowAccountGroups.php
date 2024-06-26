<?php

namespace App\Livewire\Accounts;

use App\Models\Group;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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

    public function addAccountGroup()
    {
        $this->authorize('manage_groups_and_accounts');
        $this->resetInput();
    }

    public function createAccountGroup()
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

    public function editAccountGroup(Group $group)
    {
        $this->authorize('manage_groups_and_accounts');
        $this->resetInput();

        $this->group_id = $group->id;
        $this->groupname = $group->name;

        $permissions = array_slice($group->attributesToArray(), 2);
        $this->permissions = $permissions;
    }

    public function updateAccountGroup()
    {
        $this->authorize('manage_groups_and_accounts');
        $validateData = $this->validate($this->editRules);
        $permissions = $validateData['permissions'];

        $update = array_merge(['name' => $validateData['groupname']], $permissions);
        //dd($update);

        Group::where('id', $this->group_id)->update($update);

        session()->flash('message', 'Account Group Updated Successfully');
        $this->closeModal('editAccountGroupModal');
    }

    public function deleteAccountGroup(Group $group)
    {
        $this->authorize('manage_groups_and_accounts');
        $this->group_id = $group->id;
        $this->groupname = $group->name;
    }

    public function delete()
    {
        $this->authorize('manage_groups_and_accounts');
        Group::find($this->group_id)->delete();

        $this->resetInput();
    }

    public function closeModal(?string $modalId = null)
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    private function resetInput()
    {
        $this->reset(
            'group_id',
            'groupname'
        );
    }

    public function render()
    {
        $accountGroups = Group::all();

        return view('livewire.accounts.show-account-groups', ['accountGroups' => $accountGroups]);
    }
}
