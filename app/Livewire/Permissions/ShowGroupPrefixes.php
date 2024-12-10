<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupPrefix;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowGroupPrefixes extends Component
{
    use AuthorizesRequests;

    public ?int $prefixId;

    public ?string $prefix;

    public ?string $server;

    public Group $group;

    protected function rules(): array
    {
        return [
            'prefix' => 'required|string',
            'server' => 'string|nullable',
        ];
    }

    public function addGroupPrefix(): void
    {
        $this->resetInput();
    }

    public function createGroupPrefix(): void
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

    #[On('edit')]
    public function editGroupPrefix($rowId): void
    {
        $this->resetInput();
        $groupPrefix = GroupPrefix::find($rowId);
        if ($groupPrefix == null) {
            session()->flash('error', 'GroupPrefix #'.$rowId.' not found');

            return;
        }

        $this->prefixId = $groupPrefix->id;
        $this->prefix = $groupPrefix->prefix;
        $this->server = $groupPrefix->server;
    }

    public function updateGroupPrefix(): void
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

    #[On('delete')]
    public function deleteGroupPrefix($rowId): void
    {
        $groupPrefix = GroupPrefix::find($rowId);
        if ($groupPrefix == null) {
            session()->flash('error', 'GroupPrefix #'.$rowId.' not found');

            return;
        }

        $this->prefixId = $groupPrefix->id;
        $this->prefix = $groupPrefix->prefix;
    }

    public function delete(): void
    {
        GroupPrefix::find($this->prefixId)->delete();
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
        $this->prefixId = null;
        $this->prefix = null;
        $this->server = null;
    }

    public function render(): View
    {
        return view('livewire.permissions.show-group-prefixes');
    }
}
