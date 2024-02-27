<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupMember;
use App\Models\Permissions\PermissionPlayer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPlayerGroups extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public ?int $memberId;

    public ?string $memberName;

    public ?string $groupName;

    public PermissionPlayer $player;

    public array $groups = [];

    public string $search = '';

    protected function rules()
    {
        return [
            'groupName' => 'string|required|exists:App\Models\Permissions\Group,name',
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

    public function addPlayerGroup()
    {
        $this->resetInput();
        $this->groups = Group::all()->toArray();
    }

    public function createPlayerGroup()
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
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function editPlayerGroup(GroupMember $groupMember, Group $group)
    {
        $this->resetInput();

        $this->memberId = $groupMember->id;
        $this->memberName = $this->player->name;
        $this->groupName = $group->name;
    }

    public function updatePlayerGroup()
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
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function deletePlayerGroup(GroupMember $groupMember, Group $group)
    {
        $this->authorize('edit_permissions');

        $this->memberId = $groupMember->id;
        $this->groupName = $group->name;
        $this->memberName = $this->player->name;
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
    }

    public function render(): View
    {
        $playerGroups = GroupMember::where('playeruuid', $this->player->uuid)
            ->where(function ($query) {
                $query->orWhere('groupid', 'like', '%'.$this->search.'%')
                    ->orWhere('server', 'like', '%'.$this->search.'%');
            })->orderBy('id', 'ASC')->paginate(10);

        return view('livewire.permissions.show-player-groups')->with('playerGroups', $playerGroups);
    }
}
