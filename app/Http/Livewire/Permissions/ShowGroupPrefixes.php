<?php

namespace App\Http\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupPermission;
use App\Models\Permissions\GroupPrefix;
use Illuminate\Support\Facades\Date;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowGroupPrefixes extends Component
{

    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public ?int $prefixId;
    public int $groupId;
    public string $prefix;
    public string $server;

    public Group $group;

    protected function rules()
    {
        return [
            'groupId' => 'required|int',
            'prefix' => 'required|string',
            'server' => 'string',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function addGroupPrefix(): void
    {
        $this->resetInput();
    }

    public function createGroupPrefix() {
        $validatedData = $this->validate();


        session()->flash('message', 'Successfully Created Group Prefix');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function editGroupPrefix(GroupPrefix $groupPrefix)
    {
        $this->resetInput();

        $this->groupId = $groupPrefix->id;
        $this->prefix = $groupPrefix->prefix;
        $this->server = $groupPrefix->server;
    }

    public function updateGroupPrefix()
    {
        $validatedData = $this->validate();

        /*Group::where('id', $this->groupId)->update([
            'name' => $validatedData['name'],
            'ladder' => $validatedData['ladder'],
            'rank' => $validatedData['rank']
        ]);*/
        session()->flash('message', 'Group Prefix Updated Successfully');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteGroup(GroupPrefix $groupPrefix) {
        $this->groupId = $groupPrefix->id;
        $this->prefix = $groupPrefix->prefix;
    }

    public function delete() {
        GroupPrefix::find($this->groupId)->delete();
        $this->resetInput();
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->groupId = null;
        $this->prefix = null;
        $this->server = null;
    }

    public function render(): View
    {
        $groupPrefixes = GroupPrefix::where('groupid', $this->group->id)->orderBy('id', 'ASC')->paginate(10);
        return view('livewire.permissions.show-group-prefixes')->with('prefixes', $groupPrefixes);
    }
}
