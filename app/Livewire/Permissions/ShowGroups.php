<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupMember;
use App\Models\Permissions\GroupParent;
use App\Models\Permissions\GroupPermission;
use App\Models\Permissions\GroupPrefix;
use App\Models\Permissions\GroupSuffix;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowGroups extends Component
{
    use AuthorizesRequests;

    public ?int $groupId;

    public ?int $rank;

    public ?string $name;

    public ?string $ladder;

    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'ladder' => 'required|string',
            'rank' => 'required|integer',
        ];
    }

    #[On('info')]
    public function showGroup($rowId): void
    {
        $group = Group::find($rowId);
        if ($group == null) {
            session()->flash('error', 'Group #'.$rowId.' not found');

            return;
        }

        $this->groupId = $group->id;
        $this->name = $group->name;
        $this->ladder = $group->ladder;
        $this->rank = $group->rank;
    }

    public function addGroup(): void
    {
        $this->resetInput();
    }

    public function createGroup(): void
    {
        // name has to be unique.
        $validatedData = $this->validate([
            'name' => 'required|string|unique:App\Models\Permissions\Group,name',
            'ladder' => 'required|string',
            'rank' => 'required|integer',
        ]);
        Group::create([
            'name' => $validatedData['name'],
            'ladder' => $validatedData['ladder'],
            'rank' => $validatedData['rank'],
        ]);

        session()->flash('message', 'Successfully Created Group');
        $this->closeModal('addGroupModal');
    }

    #[On('edit')]
    public function editGroup($rowId): void
    {
        $this->resetInput();

        $group = Group::find($rowId);
        if ($group == null) {
            session()->flash('error', 'Group #'.$rowId.' not found');

            return;
        }

        $this->groupId = $group->id;
        $this->name = $group->name;
        $this->ladder = $group->ladder;
        $this->rank = $group->rank;
    }

    public function updateGroup(): void
    {
        $validatedData = $this->validate();

        Group::where('id', $this->groupId)->update([
            'name' => $validatedData['name'],
            'ladder' => $validatedData['ladder'],
            'rank' => $validatedData['rank'],
        ]);
        session()->flash('message', 'Group Updated Successfully');
        $this->closeModal('editGroupModal');
    }

    #[On('delete')]
    public function deleteGroup($rowId): void
    {
        $group = Group::find($rowId);
        if ($group == null) {
            session()->flash('error', 'Group #'.$rowId.' not found');

            return;
        }

        $this->groupId = $group->id;
        $this->name = $group->name;
    }

    public function delete(): void
    {
        Group::find($this->groupId)->delete();
        GroupPermission::where('groupid', $this->groupId)->delete();
        GroupParent::where('groupid', $this->groupId)->delete();
        GroupPrefix::where('groupid', $this->groupId)->delete();
        GroupSuffix::where('groupid', $this->groupId)->delete();
        GroupMember::where('groupid', $this->groupId)->delete();
        $this->resetInput();
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
        $this->groupId = null;
        $this->name = null;
        $this->ladder = null;
        $this->rank = null;
    }

    public function render(): View
    {
        return view('livewire.permissions.show-groups');
    }
}
