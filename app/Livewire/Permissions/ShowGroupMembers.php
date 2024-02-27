<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupMember;
use App\Models\Permissions\PermissionPlayer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowGroupMembers extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    protected string $paginationTheme = 'bootstrap';

    public ?int $memberId;

    public ?string $memberName;

    public ?string $memberUUID;

    public ?string $server;

    public ?string $expires;

    public ?string $groupName;

    public Group $group;

    public string $search = '';

    protected function rules()
    {
        return [
            'memberUUID' => 'required|uuid|exists:players,uuid',
            'server' => 'string|nullable',
            'expires' => 'date|nullable',
        ];
    }

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function addGroupMember()
    {
        $this->resetInput();
    }

    public function createGroupMember()
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
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function deleteGroupMember(GroupMember $groupMember, PermissionPlayer $permissionPlayer)
    {
        $this->authorize('edit_permissions');

        $this->memberId = $groupMember->id;
        $this->groupName = $this->group->name;
        $this->memberName = $permissionPlayer->name;
    }

    public function delete()
    {
        GroupMember::find($this->memberId)->delete();
        $this->resetInput();
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->memberId = null;
        $this->memberName = null;
        $this->groupName = null;
        $this->memberUUID = null;
        $this->server = null;
        $this->expires = null;
    }

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
