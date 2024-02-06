<?php

namespace App\Http\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupParent;
use App\Models\Permissions\GroupPermission;
use App\Models\Permissions\GroupPrefix;
use App\Models\Permissions\GroupSuffix;
use Illuminate\Support\Facades\Date;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowGroupParents extends Component
{

    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public Group $group;

    public function render(): View
    {
        $groupParents = GroupParent::where('groupid', $this->group->id)->orderBy('id', 'ASC')->paginate(10);
        return view('livewire.permissions.show-group-parents')->with('parents', $groupParents);
    }
}
