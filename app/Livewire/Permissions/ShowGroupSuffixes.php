<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupSuffix;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowGroupSuffixes extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    protected string $paginationTheme = 'bootstrap';

    public ?int $suffixId;

    public ?string $suffix;

    public ?string $server;

    public Group $group;

    protected function rules()
    {
        return [
            'suffix' => 'required|string',
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

    public function addGroupSuffix(): void
    {
        $this->resetInput();
    }

    public function createGroupSuffix()
    {
        $validatedData = $this->validate();
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];

        GroupSuffix::create([
            'groupid' => $this->group->id,
            'suffix' => $validatedData['suffix'],
            'server' => $server,
        ]);

        session()->flash('message', 'Successfully Created Group Suffix');
        $this->closeModal('addGroupSuffixModal');
    }

    public function editGroupSuffix(GroupSuffix $groupSuffix)
    {
        $this->resetInput();

        $this->suffixId = $groupSuffix->id;
        $this->suffix = $groupSuffix->suffix;
        $this->server = $groupSuffix->server;
    }

    public function updateGroupSuffix()
    {
        $validatedData = $this->validate();
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];

        GroupSuffix::where('id', $this->suffixId)->update([
            'suffix' => $validatedData['suffix'],
            'server' => $server,
        ]);
        session()->flash('message', 'Group Suffix Updated Successfully');
        $this->closeModal('editGroupSuffixModal');
    }

    public function deleteGroupSuffix(GroupSuffix $groupSuffix)
    {
        $this->suffixId = $groupSuffix->id;
        $this->suffix = $groupSuffix->suffix;
    }

    public function delete()
    {
        GroupSuffix::find($this->suffixId)->delete();
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
