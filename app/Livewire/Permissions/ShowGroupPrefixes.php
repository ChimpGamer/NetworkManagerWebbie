<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupPrefix;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowGroupPrefixes extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    protected string $paginationTheme = 'bootstrap';

    public ?int $prefixId;

    public ?string $prefix;

    public ?string $server;

    public Group $group;

    protected function rules()
    {
        return [
            'prefix' => 'required|string',
            'server' => 'string|nullable',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
        if ($fields == 'search') {
            $this->resetPage();
        }
    }

    public function addGroupPrefix(): void
    {
        $this->resetInput();
    }

    public function createGroupPrefix()
    {
        $validatedData = $this->validate();
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];

        GroupPrefix::create([
            'groupid' => $this->group->id,
            'prefix' => $validatedData['prefix'],
            'server' => $server,
        ]);

        session()->flash('message', 'Successfully Created Group Prefix');
        $this->closeModal('addGroupPrefixModal');
    }

    public function editGroupPrefix(GroupPrefix $groupPrefix)
    {
        $this->resetInput();

        $this->prefixId = $groupPrefix->id;
        $this->prefix = $groupPrefix->prefix;
        $this->server = $groupPrefix->server;
    }

    public function updateGroupPrefix()
    {
        $validatedData = $this->validate();
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];

        GroupPrefix::where('id', $this->prefixId)->update([
            'prefix' => $validatedData['prefix'],
            'server' => $server,
        ]);
        session()->flash('message', 'Group Prefix Updated Successfully');
        $this->closeModal('editGroupPrefixModal');
    }

    public function deleteGroupPrefix(GroupPrefix $groupPrefix)
    {
        $this->prefixId = $groupPrefix->id;
        $this->prefix = $groupPrefix->prefix;
    }

    public function delete()
    {
        GroupPrefix::find($this->prefixId)->delete();
        $this->resetInput();
    }

    public function closeModal(?string $modalId = null)
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    private function resetInput()
    {
        $this->prefixId = null;
        $this->prefix = null;
        $this->server = null;
    }

    public function render(): View
    {
        $groupPrefixes = GroupPrefix::where('groupid', $this->group->id)->orderBy('id', 'ASC')->paginate(10);

        return view('livewire.permissions.show-group-prefixes')->with('prefixes', $groupPrefixes);
    }
}
