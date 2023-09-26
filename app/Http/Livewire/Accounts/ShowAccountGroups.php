<?php

namespace App\Http\Livewire\Accounts;

use App\Models\Group;
use Livewire\Component;

class ShowAccountGroups extends Component
{
    public int $group_id = -1;
    public string $groupname = '';

    protected function rules() {
        return [
            'groupname' => 'required|string'
        ];
    }

    public function addAccountGroup()
    {
        $this->resetInput();
    }

    public function createAccountGroup() {
        $validatedData = $this->validate();
        $groupname = $validatedData['groupname'];

        Group::create([
           'name' => $groupname
        ]);

        session()->flash('message', 'Successfully Added Account Group');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteAccountGroup(Group $group) {
        $this->group_id = $group->id;
        $this->groupname = $group->name;
    }

    public function delete() {
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
