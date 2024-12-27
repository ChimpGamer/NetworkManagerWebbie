<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupParent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowGroupParents extends Component
{
    use AuthorizesRequests;

    public ?int $parentId;

    public ?string $parentName;

    public ?string $groupName;

    public Group $group;

    public array $groups = [];

    public function rules(): array
    {
        return [
            'groupName' => 'required|string|exists:App\Models\Permissions\Group,name',
        ];
    }

    public function addGroupParent(): void
    {
        $this->resetInput();
        $this->groups = Group::all()->toArray();
    }

    public function createGroupParent(): void
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
        $this->closeModal('addGroupParentModal');
        $this->refreshTable();
    }

    #[On('delete')]
    public function deleteGroupParent($rowId): void
    {
        $groupParent = GroupParent::find($rowId);
        if ($groupParent == null) {
            session()->flash('error', 'GroupParent #'.$rowId.' not found');

            return;
        }

        $this->parentId = $rowId;
        $this->parentName = $groupParent->parentGroup->name;
        $this->groupName = $this->group->name;
    }

    public function delete(): void
    {
        GroupParent::find($this->parentId)->delete();
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

    public function resetInput(): void
    {
        $this->parentId = null;
        $this->groupName = null;
        $this->groups = [];
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-group-parents-table');
    }

    public function render(): View
    {
        return view('livewire.permissions.show-group-parents');
    }
}
