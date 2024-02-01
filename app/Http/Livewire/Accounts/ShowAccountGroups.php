<?php

namespace App\Http\Livewire\Accounts;

use App\Models\Group;
use Livewire\Component;

class ShowAccountGroups extends Component
{
    public int $group_id = -1;

    public string $groupname = '';

    public array $permissions = [];

    protected function rules()
    {
        return [
            'groupname' => 'required|string',
            'permissions' => 'required|array',
            'permissions.*' => 'required|boolean',
        ];
    }

    public function addAccountGroup()
    {
        $this->resetInput();
    }

    public function createAccountGroup()
    {
        $validatedData = $this->validate();
        $groupname = $validatedData['groupname'];

        Group::create([
            'name' => $groupname,
        ]);

        session()->flash('message', 'Successfully Added Account Group');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function editAccountGroup(Group $group)
    {
        $this->resetInput();

        $this->group_id = $group->id;
        $this->groupname = $group->name;

        $permissions = array_slice($group->attributesToArray(), 2);
        unset($permissions['administrator']);
        $this->permissions = $permissions;
    }

    public function updateAccountGroup()
    {
        $validateData = $this->validate();
        $permissions = $validateData['permissions'];

        $update = array_merge(['name' => $validateData['groupname']], $permissions);
        //dd($update);

        Group::where('id', $this->group_id)->update($update);

        session()->flash('message', 'Account Group Updated Successfully');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteAccountGroup(Group $group)
    {
        $this->group_id = $group->id;
        $this->groupname = $group->name;
    }

    public function delete()
    {
        Group::find($this->group_id)->delete();

        $this->resetInput();
    }

    public function closeModal()
    {
        $this->resetInput();
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
