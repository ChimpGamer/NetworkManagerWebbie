<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupMember;
use App\Models\Permissions\PermissionPlayer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowGroupMembers extends Component
{
    use AuthorizesRequests;

    public ?int $memberId;

    public ?string $memberName;

    public ?string $memberUUID;

    public ?string $server;

    public ?string $expires;

    public ?string $groupName;

    public Group $group;

    protected function rules(): array
    {
        return [
            'memberUUID' => 'required|uuid|exists:players,uuid',
            'server' => 'string|nullable',
            'expires' => 'date|nullable',
        ];
    }

    public function addGroupMember(): void
    {
        $this->resetInput();
    }

    public function createGroupMember(): void
    {
        $validatedData = $this->validate();
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        $permissionPlayer = PermissionPlayer::find($validatedData['memberUUID']);
        if ($permissionPlayer == null) {
            session()->flash('error', 'Something went wrong trying to add member!');

            return;
        }

        $exists = GroupMember::where('playeruuid', $permissionPlayer->uuid)->where('groupid', $this->group->id)->where('server', $server)->exists();
        if ($exists) {
            session()->flash('error', $permissionPlayer->name.' already has this group!');

            return;
        }

        GroupMember::create([
            'playeruuid' => $permissionPlayer->uuid,
            'groupid' => $this->group->id,
            'server' => $server,
            'expires' => $expires,
        ]);
        session()->flash('message', 'Successfully Added Player to Group');
        $this->closeModal('addGroupMemberModal');
        $this->refreshTable();
    }

    #[On('delete')]
    public function deleteGroupMember($rowId): void
    {
        $this->authorize('edit_permissions');

        $groupMember = GroupMember::find($rowId);
        if ($groupMember == null) {
            session()->flash('error', 'GroupMember #'.$rowId.' not found');

            return;
        }
        $permissionPlayer = $groupMember->permissionPlayer;

        $this->memberId = $groupMember->id;
        $this->groupName = $this->group->name;
        $this->memberName = $permissionPlayer->name;
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
        $this->memberUUID = null;
        $this->server = null;
        $this->expires = null;
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-group-members-table');
    }

    public function render(): View
    {
        return view('livewire.permissions.show-group-members');
    }
}
