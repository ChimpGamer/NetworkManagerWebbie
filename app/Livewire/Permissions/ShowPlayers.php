<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\PermissionPlayer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPlayers extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    protected string $paginationTheme = 'bootstrap';

    public ?string $playerUuid;

    public ?string $name;

    public ?string $prefix;

    public ?string $suffix;

    public string $search = '';

    protected function rules()
    {
        return [
            'prefix' => 'string',
            'suffix' => 'string',
        ];
    }

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function showPlayer(PermissionPlayer $permissionPlayer)
    {
        $this->playerUuid = $permissionPlayer->uuid;
        $this->name = $permissionPlayer->name;
        $this->prefix = $permissionPlayer->prefix;
        $this->suffix = $permissionPlayer->suffix;
    }

    public function editPlayer(PermissionPlayer $permissionPlayer)
    {
        $this->resetInput();

        $this->playerUuid = $permissionPlayer->uuid;
        $this->name = $permissionPlayer->name;
        $this->prefix = $permissionPlayer->prefix;
        $this->suffix = $permissionPlayer->suffix;
    }

    public function updatePermissionPlayer()
    {
        $validatedData = $this->validate();

        $prefix = empty($validatedData['prefix']) ? '' : $validatedData['prefix'];
        $suffix = empty($validatedData['suffix']) ? '' : $validatedData['suffix'];

        PermissionPlayer::where('uuid', $this->playerUuid)->update([
            'prefix' => $prefix,
            'suffix' => $suffix,
        ]);
        session()->flash('message', 'Permission Player Updated Successfully');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->playerUuid = null;
        $this->name = null;
        $this->prefix = null;
        $this->suffix = null;
    }

    public function render(): View
    {
        $players = PermissionPlayer::where(function ($query) {
            $query->where('uuid', 'like', '%'.$this->search.'%')
                ->orWhere('name', 'like', '%'.$this->search.'%')
                ->orWhere('prefix', 'like', '%'.$this->search.'%')
                ->orWhere('suffix', 'like', '%'.$this->search.'%');
        })->orderBy('name', 'ASC')->paginate(10, pageName: 'players-page');

        return view('livewire.permissions.show-players')->with('players', $players);
    }
}
