<?php

namespace App\Http\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupPermission;
use Illuminate\Support\Facades\Date;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowGroupPermissions extends Component
{

    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public ?int $permissionId;
    public int $groupId;
    public string $permission;
    public string $world;
    public string $server;
    public Date $expires;

    public Group $group;

    protected function rules()
    {
        return [
            'groupId' => 'required|int',
            'permission' => 'required|string',
            'world' => 'string',
            'server' => 'string',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function addGroup(): void
    {
        $this->resetInput();
    }

    public function createGroup() {
        $validatedData = $this->validate();
        Group::create([
            'name' => $validatedData['name'],
            'ladder' => $validatedData['ladder'],
            'rank' => $validatedData['rank']
        ]);

        session()->flash('message', 'Successfully Created Group');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function editGroup(Group $group)
    {
        $this->resetInput();

        $this->groupId = $group->id;
        $this->name = $group->name;
        $this->ladder = $group->ladder;
        $this->rank = $group->rank;
    }

    public function updateGroup()
    {
        $validatedData = $this->validate();

        Group::where('id', $this->groupId)->update([
            'name' => $validatedData['name'],
            'ladder' => $validatedData['ladder'],
            'rank' => $validatedData['rank']
        ]);
        session()->flash('message', 'Group Updated Successfully');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteGroup(Group $group) {
        $this->groupId = $group->id;
        $this->name = $group->name;
    }

    public function delete() {
        Group::find($this->groupId)->delete();
        $this->resetInput();
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->groupId = null;
        $this->name = null;
        $this->ladder = null;
        $this->rank = null;
    }

    public function render(): View
    {
        $groupPermissions = GroupPermission::where('groupid', $this->group->id)->orderBy('id', 'ASC')->paginate(10);
        return view('livewire.permissions.show-group-permissions')->with('permissions', $groupPermissions);
    }
}
