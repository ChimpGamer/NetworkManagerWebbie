<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupSuffix;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowGroupSuffixes extends Component
{
    use AuthorizesRequests;

    public ?int $suffixId;

    public ?string $suffix;

    public ?string $server;

    public Group $group;

    protected function rules(): array
    {
        return [
            'suffix' => 'required|string',
            'server' => 'string|nullable',
        ];
    }

    public function addGroupSuffix(): void
    {
        $this->resetInput();
    }

    public function createGroupSuffix(): void
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

    #[On('edit')]
    public function editGroupSuffix($rowId): void
    {
        $this->resetInput();
        $groupSuffix = GroupSuffix::find($rowId);
        if ($groupSuffix == null) {
            session()->flash('error', 'GroupSuffix #'.$rowId.' not found');

            return;
        }

        $this->suffixId = $groupSuffix->id;
        $this->suffix = $groupSuffix->suffix;
        $this->server = $groupSuffix->server;
    }

    public function updateGroupSuffix(): void
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

    #[On('delete')]
    public function deleteGroupSuffix($rowId): void
    {
        $groupSuffix = GroupSuffix::find($rowId);
        if ($groupSuffix == null) {
            session()->flash('error', 'GroupSuffix #'.$rowId.' not found');

            return;
        }

        $this->suffixId = $groupSuffix->id;
        $this->suffix = $groupSuffix->suffix;
    }

    public function delete(): void
    {
        GroupSuffix::find($this->suffixId)->delete();
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
        $this->suffixId = null;
        $this->suffix = null;
        $this->server = null;
    }

    public function render(): View
    {
        return view('livewire.permissions.show-group-suffixes');
    }
}
