<?php

namespace App\Http\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupMember;
use App\Models\Permissions\PermissionPlayer;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowGroupMembers extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public ?int $memberId;

    public ?string $memberName;

    public ?string $groupName;

    public Group $group;

    public string $search = '';

    public function deleteGroupMember(GroupMember $groupMember, PermissionPlayer $permissionPlayer)
    {
        $this->memberId = $groupMember->id;
        $this->groupName = $this->group->name;
        $this->memberName = $permissionPlayer->name;
    }

    public function delete()
    {
        GroupMember::find($this->memberId)->delete();
        $this->resetInput();
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {

    }

    public function render(): View
    {
        $groupMembers = GroupMember::where('groupid', $this->group->id)
            ->where(function ($query) {
                $query->orWhere('playeruuid', 'like', '%'.$this->search.'%')
                    ->orWhere('server', 'like', '%'.$this->search.'%');
            })
            ->orderBy('id', 'ASC')->paginate(10);

        return view('livewire.permissions.show-group-members')->with('members', $groupMembers);
    }
}
