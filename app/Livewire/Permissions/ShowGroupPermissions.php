<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupPermission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowGroupPermissions extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public ?int $permissionId;

    public ?string $permission;

    public ?string $world;

    public ?string $server;

    public ?string $expires;

    public Group $group;

    public string $search = '';

    protected function rules()
    {
        return [
            'permission' => 'required|string',
            'world' => 'string|nullable',
            'server' => 'string|nullable',
            'expires' => 'date|nullable',
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
        if ($fields == 'search') {
            $this->resetPage();
        }
    }

    public function addGroupPermission(): void
    {
        $this->resetInput();
    }

    public function createGroupPermission()
    {
        $validatedData = $this->validate();
        $permission = $validatedData['permission'];
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];
        $world = empty($validatedData['world']) ? '' : $validatedData['world'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        $exists = $this->permissionExists($this->group, $permission, $server, $world, $expires);
        if ($exists) {
            session()->flash('error', 'Group '.$this->group->name.' already has the '.$permission.' permission.');

            return;
        }

        if ($expires != null) {
            $similarPermissions = GroupPermission::where('groupid', $this->group->id)
                ->where('permission', $permission)
                ->where('server', $server)
                ->where('world', $world)
                ->get();

            foreach ($similarPermissions as $perm) {
                if ($perm->willExpire()) {
                    $updated = $perm->update(['expires' => $expires]);
                    if (! $updated) {
                        session()->flash('message', 'Could not update permission expiration date.');
                        $this->resetInput();
                        $this->dispatch('close-modal');

                        return;
                    }
                    session()->flash('message', 'Permission expiration changed.');
                    $this->resetInput();
                    $this->dispatch('close-modal');

                    return;
                }
            }
        }

        GroupPermission::create([
            'groupid' => $this->group->id,
            'permission' => $permission,
            'server' => $server,
            'world' => $world,
            'expires' => $expires,
        ]);

        session()->flash('message', 'Successfully Created Group Permission');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function editGroupPermission(GroupPermission $groupPermission)
    {
        $this->resetInput();

        $this->permissionId = $groupPermission->id;
        $this->permission = $groupPermission->permission;
        $this->server = $groupPermission->server;
        $this->world = $groupPermission->world;
        $this->expires = $groupPermission->expires;
    }

    public function updateGroupPermission()
    {
        $validatedData = $this->validate();
        $permission = $validatedData['permission'];
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];
        $world = empty($validatedData['world']) ? '' : $validatedData['world'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        $exists = $this->permissionExists($this->group, $permission, $server, $world, $expires);
        if ($exists) {
            session()->flash('error', 'Group '.$this->group->name.' already has the '.$permission.' permission.');

            return;
        }

        GroupPermission::where('id', $this->permissionId)->update([
            'permission' => $permission,
            'server' => $server,
            'world' => $world,
            'expires' => $expires,
        ]);
        session()->flash('message', 'Group Permission Updated Successfully');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function deleteGroupPermission(GroupPermission $groupPermission)
    {
        $this->permissionId = $groupPermission->id;
        $this->permission = $groupPermission->permission;
    }

    public function delete()
    {
        GroupPermission::find($this->permissionId)->delete();
        $this->resetInput();
    }

    private function permissionExists(Group $group, string $permission, string $server, string $world, $expires): bool
    {
        return GroupPermission::where('groupid', $group->id)
            ->where('permission', $permission)
            ->where('server', $server)
            ->where('world', $world)
            ->where('expires', $expires)
            ->exists();
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->permissionId = null;
        $this->permission = null;
        $this->world = null;
        $this->server = null;
        $this->expires = null;
    }

    public function render(): View
    {
        $groupPermissions = GroupPermission::where('groupid', $this->group->id)
            ->where(function ($query) {
                $query->orWhere('permission', 'like', '%'.$this->search.'%')
                    ->orWhere('world', 'like', '%'.$this->search.'%')
                    ->orWhere('server', 'like', '%'.$this->search.'%');
            })->orderBy('id', 'ASC')->paginate(10);

        return view('livewire.permissions.show-group-permissions')->with('permissions', $groupPermissions);
    }
}
