<?php

namespace App\Http\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupMember;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowGroupMembers extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public Group $group;

    public string $search = '';

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
