<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupPermission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowGroupPermissions extends Component
{
    use AuthorizesRequests;

    public ?int $permissionId;

    public ?string $permission;

    public ?string $world;

    public ?string $server;

    public ?string $expires;

    public Group $group;

    protected function rules(): array
    {
        return [
            'permission' => 'required|string',
            'world' => 'string|nullable',
            'server' => 'string|nullable',
            'expires' => 'date|nullable',
        ];
    }

    public function addGroupPermission(): void
    {
        $this->resetInput();
    }

    public function createGroupPermission(): void
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
        $this->closeModal('addGroupPermissionModal');
        $this->refreshTable();
    }

    #[On('edit')]
    public function editGroupPermission($rowId): void
    {
        $this->resetInput();
        $groupPermission = GroupPermission::find($rowId);
        if ($groupPermission == null) {
            session()->flash('error', 'GroupPermission #'.$rowId.' not found');

            return;
        }

        $this->permissionId = $groupPermission->id;
        $this->permission = $groupPermission->permission;
        $this->server = $groupPermission->server;
        $this->world = $groupPermission->world;
        $this->expires = $groupPermission->expires;
    }

    public function updateGroupPermission(): void
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
        $this->closeModal('editGroupPermissionModal');
        $this->refreshTable();
    }

    #[On('delete')]
    public function deleteGroupPermission($rowId): void
    {
        $groupPermission = GroupPermission::find($rowId);
        if ($groupPermission == null) {
            session()->flash('error', 'GroupPermission #'.$rowId.' not found');

            return;
        }

        $this->permissionId = $groupPermission->id;
        $this->permission = $groupPermission->permission;
    }

    public function delete(): void
    {
        GroupPermission::find($this->permissionId)->delete();
        $this->resetInput();
        $this->refreshTable();
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

    public function closeModal(?string $modalId = null): void
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    private function resetInput(): void
    {
        $this->permissionId = null;
        $this->permission = null;
        $this->world = null;
        $this->server = null;
        $this->expires = null;
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-group-permissions-table');
    }

    public function render(): View
    {
        return view('livewire.permissions.show-group-permissions');
    }
}
