<?php

namespace App\Http\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupPermission;
use App\Models\Permissions\GroupPrefix;
use App\Models\Permissions\GroupSuffix;
use Illuminate\Support\Facades\Date;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowGroupSuffixes extends Component
{

    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public ?int $suffixId;
    public int $groupId;
    public string $suffix;
    public string $server;

    public Group $group;

    protected function rules()
    {
        return [
            'groupId' => 'required|int',
            'suffix' => 'required|string',
            'server' => 'string',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function addGroupSuffix(): void
    {
        $this->resetInput();
    }

    public function createGroupSuffix() {
        $validatedData = $this->validate();


        session()->flash('message', 'Successfully Created Group Suffix');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function editGroupSuffix(GroupSuffix $groupSuffix)
    {
        $this->resetInput();

        $this->groupId = $groupSuffix->id;
        $this->suffix = $groupSuffix->prefix;
        $this->server = $groupSuffix->server;
    }

    public function updateGroupSuffix()
    {
        $validatedData = $this->validate();

        /*Group::where('id', $this->groupId)->update([
            'name' => $validatedData['name'],
            'ladder' => $validatedData['ladder'],
            'rank' => $validatedData['rank']
        ]);*/
        session()->flash('message', 'Group Suffix Updated Successfully');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteGroupSuffix(GroupSuffix $groupSuffix) {
        $this->groupId = $groupSuffix->id;
        $this->suffix = $groupSuffix->prefix;
    }

    public function deleteSuffix() {
        GroupSuffix::find($this->groupId)->delete();
        $this->resetInput();
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->suffixId = null;
        $this->suffix = null;
        $this->server = null;
    }

    public function render(): View
    {
        $groupSuffixes = GroupSuffix::where('groupid', $this->group->id)->orderBy('id', 'ASC')->paginate(10);
        return view('livewire.permissions.show-group-suffixes')->with('suffixes', $groupSuffixes);
    }
}
