<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\PermissionPlayer;
use App\Models\Permissions\PlayerPermission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPlayerPermissions extends Component
{
    use AuthorizesRequests;

    public ?int $permissionId;

    public ?string $permission;

    public ?string $world;

    public ?string $server;

    public ?string $expires;

    public PermissionPlayer $player;

    protected function rules(): array
    {
        return [
            'permission' => 'required|string',
            'world' => 'string|nullable',
            'server' => 'string|nullable',
            'expires' => 'date|nullable',
        ];
    }

    public function addPlayerPermission(): void
    {
        $this->resetInput();
    }

    public function createPlayerPermission(): void
    {
        $validatedData = $this->validate();
        $permission = $validatedData['permission'];
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];
        $world = empty($validatedData['world']) ? '' : $validatedData['world'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        $exists = $this->permissionExists($this->player, $permission, $server, $world, $expires);
        if ($exists) {
            session()->flash('error', 'Player '.$this->player->name.' already has the '.$permission.' permission.');

            return;
        }

        if ($expires != null) {
            $similarPermissions = PlayerPermission::where('playeruuid', $this->player->uuid)
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

        PlayerPermission::create([
            'playeruuid' => $this->player->uuid,
            'permission' => $validatedData['permission'],
            'server' => $server,
            'world' => $world,
            'expires' => $expires,
        ]);

        session()->flash('message', 'Successfully Created Player Permission');
        $this->closeModal('addPlayerPermissionModal');
        $this->refreshTable();
    }

    #[On('edit')]
    public function editPlayerPermission($rowId): void
    {
        $this->resetInput();
        $playerPermission = PlayerPermission::find($rowId);
        if ($playerPermission == null) {
            session()->flash('error', 'PlayerPermission #'.$rowId.' not found');

            return;
        }

        $this->permissionId = $playerPermission->id;
        $this->permission = $playerPermission->permission;
        $this->server = $playerPermission->server;
        $this->world = $playerPermission->world;
        $this->expires = $playerPermission->expires;
    }

    public function updatePlayerPermission(): void
    {
        $validatedData = $this->validate();
        $permission = $validatedData['permission'];
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];
        $world = empty($validatedData['world']) ? '' : $validatedData['world'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        $exists = $this->permissionExists($this->player, $permission, $server, $world, $expires);
        if ($exists) {
            session()->flash('error', 'Player '.$this->player->name.' already has the '.$permission.' permission.');

            return;
        }

        PlayerPermission::where('id', $this->permissionId)->update([
            'permission' => $validatedData['permission'],
            'server' => $server,
            'world' => $world,
            'expires' => $expires,
        ]);
        session()->flash('message', 'Player Permission Updated Successfully');
        $this->closeModal('editPlayerPermissionModal');
        $this->refreshTable();
    }

    #[On('delete')]
    public function deletePlayerPermission($rowId): void
    {
        $playerPermission = PlayerPermission::find($rowId);
        if ($playerPermission == null) {
            session()->flash('error', 'PlayerPermission #'.$rowId.' not found');

            return;
        }
        $this->permissionId = $playerPermission->id;
        $this->permission = $playerPermission->permission;
    }

    public function delete(): void
    {
        PlayerPermission::find($this->permissionId)->delete();
        $this->resetInput();
        $this->refreshTable();
    }

    private function permissionExists(PermissionPlayer $permissionPlayer, string $permission, string $server, string $world, $expires): bool
    {
        return PlayerPermission::where('playeruuid', $permissionPlayer->uuid)
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
        $this->dispatch('pg:eventRefresh-permission-player-permissions-table');
    }

    public function render(): View
    {
        return view('livewire.permissions.show-player-permissions');
    }
}
