<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\GroupMember;
use App\Models\Permissions\PermissionPlayer;
use App\Models\Permissions\PlayerPermission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowPlayers extends Component
{
    use AuthorizesRequests;

    public ?string $playerUuid;

    public ?string $name;

    public ?string $prefix;

    public ?string $suffix;

    protected function rules(): array
    {
        return [
            'prefix' => 'string',
            'suffix' => 'string',
        ];
    }

    #[On('info')]
    public function showPlayer($rowId): void
    {
        $permissionPlayer = PermissionPlayer::find($rowId);
        if ($permissionPlayer == null) {
            session()->flash('error', 'Permission Player #'.$rowId.' not found');

            return;
        }

        $this->playerUuid = $permissionPlayer->uuid;
        $this->name = $permissionPlayer->name;
        $this->prefix = $permissionPlayer->prefix;
        $this->suffix = $permissionPlayer->suffix;
    }

    #[On('edit')]
    public function editPlayer($rowId): void
    {
        $this->resetInput();
        $permissionPlayer = PermissionPlayer::find($rowId);
        if ($permissionPlayer == null) {
            session()->flash('error', 'Permission Player #'.$rowId.' not found');

            return;
        }

        $this->playerUuid = $permissionPlayer->uuid;
        $this->name = $permissionPlayer->name;
        $this->prefix = $permissionPlayer->prefix;
        $this->suffix = $permissionPlayer->suffix;
    }

    public function updatePermissionPlayer(): void
    {
        $validatedData = $this->validate();

        $prefix = empty($validatedData['prefix']) ? '' : $validatedData['prefix'];
        $suffix = empty($validatedData['suffix']) ? '' : $validatedData['suffix'];

        PermissionPlayer::where('uuid', $this->playerUuid)->update([
            'prefix' => $prefix,
            'suffix' => $suffix,
        ]);
        session()->flash('message', 'Permission Player Updated Successfully');
        $this->closeModal('editPermissionPlayerModal');
        $this->refreshTable();
    }

    #[On('delete')]
    public function deletePlayer($rowId): void
    {
        $this->resetInput();
        $permissionPlayer = PermissionPlayer::find($rowId);
        if ($permissionPlayer == null) {
            session()->flash('error', 'Permission Player #'.$rowId.' not found');

            return;
        }

        $this->playerUuid = $permissionPlayer->uuid;
        $this->name = $permissionPlayer->name;
    }

    public function delete(): void
    {
        PermissionPlayer::find($this->playerUuid)->delete();
        PlayerPermission::where('playeruuid', $this->playerUuid)->delete();
        GroupMember::where('playeruuid', $this->playerUuid)->delete();
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
        $this->playerUuid = null;
        $this->name = null;
        $this->prefix = null;
        $this->suffix = null;
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-permission-players-table');
    }

    public function render(): View
    {
        return view('livewire.permissions.show-players');
    }
}
