<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupMember;
use App\Models\Permissions\PermissionPlayer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowPlayerGroups extends Component
{
    use AuthorizesRequests;

    public ?int $memberId;

    public ?string $memberName;

    public ?string $groupName;

    public ?string $server;

    public ?string $expires;

    public PermissionPlayer $player;

    public array $groups = [];

    protected function rules(): array
    {
        return [
            'groupName' => 'string|required|exists:App\Models\Permissions\Group,name',
            'server' => 'string|nullable',
            'expires' => 'date|nullable',
        ];
    }

    public function addPlayerGroup(): void
    {
        $this->resetInput();
        $this->groups = Group::all()->toArray();
    }

    public function createPlayerGroup(): void
    {
        $validatedData = $this->validate();
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        $group = Group::where('name', $this->groupName)->first();
        if ($group == null) {
            session()->flash('error', 'Something went wrong trying to add group!');

            return;
        }

        $exists = GroupMember::where('playeruuid', $this->player->uuid)->where('groupid', $group->id)->where('server', $server)->exists();
        if ($exists) {
            session()->flash('error', $this->memberName.' already has this group!');

            return;
        }

        GroupMember::create([
            'playeruuid' => $this->player->uuid,
            'groupid' => $group->id,
            'server' => $server,
            'expires' => $expires,
        ]);
        session()->flash('message', 'Successfully Created Player Group');
        $this->closeModal('addPlayerGroupModal');
        $this->refreshTable();
    }

    #[On('edit')]
    public function editPlayerGroup($rowId): void
    {
        $this->resetInput();
        $groupMember = GroupMember::find($rowId);
        if ($groupMember == null) {
            session()->flash('error', 'GroupMember #'.$rowId.' not found');

            return;
        }

        $this->memberId = $groupMember->id;
        $this->memberName = $this->player->name;
        $this->groupName = $groupMember->group->name;
    }

    public function updatePlayerGroup(): void
    {
        $validatedData = $this->validate();
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        $group = Group::where('name', $this->groupName)->first();
        if ($group == null) {
            session()->flash('error', 'Something went wrong trying to add group!');

            return;
        }

        $exists = GroupMember::where('playeruuid', $this->player->uuid)->where('groupid', $group->id)->where('server', $server)->exists();
        if ($exists) {
            session()->flash('error', $this->memberName.' already has this group!');

            return;
        }

        GroupMember::where('id', $this->memberId)->update([
            'playeruuid' => $this->player->uuid,
            'groupid' => $group->id,
            'server' => $server,
            'expires' => $expires,
        ]);
        session()->flash('message', 'Player Group Updated Successfully');
        $this->closeModal('editPlayerGroupModal');
        $this->refreshTable();
    }

    #[On('delete')]
    public function deletePlayerGroup($rowId): void
    {
        $this->authorize('edit_permissions');
        $groupMember = GroupMember::find($rowId);
        if ($groupMember == null) {
            session()->flash('error', 'GroupMember #'.$rowId.' not found');

            return;
        }

        $this->memberId = $groupMember->id;
        $this->groupName = $groupMember->group->name;
        $this->memberName = $this->player->name;
    }

    public function delete(): void
    {
        GroupMember::find($this->memberId)->delete();
        $this->resetInput();
        $this->refreshTable();
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
        $this->memberId = null;
        $this->memberName = null;
        $this->groupName = null;
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-permission-player-groups-table');
    }

    public function render(): View
    {
        return view('livewire.permissions.show-player-groups');
    }
}
