<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupPermission;
use App\Models\Permissions\PermissionPlayer;
use App\Models\Permissions\PlayerPermission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPlayerPermissions extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    protected string $paginationTheme = 'bootstrap';

    public ?int $permissionId;

    public ?string $permission;

    public ?string $world;

    public ?string $server;

    public ?string $expires;

    public PermissionPlayer $player;

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

    public function addPlayerPermission(): void
    {
        $this->resetInput();
    }

    public function createPlayerPermission()
    {
        $validatedData = $this->validate();
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];
        $world = empty($validatedData['world']) ? '' : $validatedData['world'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        PlayerPermission::create([
            'playeruuid' => $this->player->uuid,
            'permission' => $validatedData['permission'],
            'server' => $server,
            'world' => $world,
            'expires' => $expires,
        ]);

        session()->flash('message', 'Successfully Created Player Permission');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function editPlayerPermission(PlayerPermission $playerPermission)
    {
        $this->resetInput();

        $this->permissionId = $playerPermission->id;
        $this->permission = $playerPermission->permission;
        $this->server = $playerPermission->server;
        $this->world = $playerPermission->world;
        $this->expires = $playerPermission->expires;
    }

    public function updatePlayerPermission()
    {
        $validatedData = $this->validate();
        $server = empty($validatedData['server']) ? '' : $validatedData['server'];
        $world = empty($validatedData['world']) ? '' : $validatedData['world'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        PlayerPermission::where('id', $this->permissionId)->update([
            'permission' => $validatedData['permission'],
            'server' => $server,
            'world' => $world,
            'expires' => $expires,
        ]);
        session()->flash('message', 'Player Permission Updated Successfully');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function deletePlayerPermission(PlayerPermission $playerPermission)
    {
        $this->permissionId = $playerPermission->id;
        $this->permission = $playerPermission->permission;
    }

    public function delete()
    {
        PlayerPermission::find($this->permissionId)->delete();
        $this->resetInput();
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
        $playerPermissions = PlayerPermission::where('playeruuid', $this->player->uuid)
            ->where(function ($query) {
                $query->orWhere('permission', 'like', '%'.$this->search.'%')
                    ->orWhere('world', 'like', '%'.$this->search.'%')
                    ->orWhere('server', 'like', '%'.$this->search.'%');
            })->orderBy('id', 'ASC')->paginate(10);

        return view('livewire.permissions.show-player-permissions')->with('permissions', $playerPermissions);
    }
}
