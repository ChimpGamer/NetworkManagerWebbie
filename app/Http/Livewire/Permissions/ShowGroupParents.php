<?php

namespace App\Http\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupParent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowGroupParents extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    protected string $paginationTheme = 'bootstrap';

    public ?int $parentId;

    public ?string $parentName;

    public ?string $groupName;

    public Group $group;

    public array $groups = [];

    public function rules()
    {
        return [
            'groupName' => 'required|string|exists:App\Models\Permissions\Group,name',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function addGroupParent(): void
    {
        $this->resetInput();
        $this->groups = Group::all()->toArray();
    }

    public function createGroupParent()
    {
        $validatedData = $this->validate();
        $group = Group::where('name', $validatedData['groupName'])->first();
        if ($group == null) {
            session()->flash('error', 'Something went wrong trying to create group parent!');

            return;
        }
        if ($this->group == $group) {
            session()->flash('error', 'You can\'t add this group as parent!');

            return;
        }
        $exists = GroupParent::where('groupid', $this->group->id)->where('parentgroupid', $group->id)->exists();
        if ($exists) {
            session()->flash('error', 'Group '.$group->name.' is already a parent of '.$this->group->name.'.');

            return;
        }

        GroupParent::create([
            'groupid' => $this->group->id,
            'parentgroupid' => $group->id,
        ]);

        session()->flash('message', 'Successfully Created Group Parent');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteGroupParent(GroupParent $groupParent, Group $group)
    {
        $this->parentId = $groupParent->id;
        $this->parentName = $group->name;
        $this->groupName = $this->group->name;
    }

    public function delete()
    {
        GroupParent::find($this->parentId)->delete();
        $this->resetInput();
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->parentId = null;
        $this->groupName = null;
        $this->groups = [];
    }

    public function render(): View
    {
        $groupParents = GroupParent::where('groupid', $this->group->id)->orderBy('id', 'ASC')->paginate(10);

        return view('livewire.permissions.show-group-parents')->with('parents', $groupParents);
    }
}
