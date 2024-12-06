<?php

namespace App\Livewire\Servers;

use App\Models\Server;
use App\Models\ServerGroup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowServerGroups extends Component
{
    use AuthorizesRequests;

    public int $groupId;

    public string $groupname;

    public array $balancemethods = self::BALANCE_METHODS;

    public string $balancemethod;

    public $currentServers = [];

    public $serversSelection = [];

    public int $deleteId;

    const BALANCE_METHODS = ['RANDOM', 'RANDOM_LOWEST', 'RANDOM_FILLER', 'PROGRESSIVE_LOWEST'];

    protected function rules(): array
    {
        return [
            'groupname' => 'required|string|min:3',
            'balancemethod' => 'required|string|in:'.implode(',', self::BALANCE_METHODS),
            'serversSelection' => 'required|array',
            'serversSelection.*' => 'integer|exists:servers,id',
        ];
    }

    #[On('server-group-info')]
    public function showServerGroup($rowId): void
    {
        $serverGroup = ServerGroup::find($rowId);
        if ($serverGroup == null) {
            session()->flash('error', 'Server Group $'.$rowId.' not found');

            return;
        }

        $this->groupId = $serverGroup->id;
        $this->groupname = $serverGroup->groupname;
        $this->balancemethod = $serverGroup->balancemethodtype;

        $this->currentServers = Server::whereIn('id', $serverGroup->servers)->get();
    }

    #[On('delete-server-group')]
    public function deleteServerGroup($rowId): void
    {
        $serverGroup = ServerGroup::find($rowId);
        if ($serverGroup == null) {
            session()->flash('error', 'Server Group $'.$rowId.' not found');

            return;
        }

        $this->deleteId = $serverGroup->id;
        $this->groupname = $serverGroup->groupname;
    }

    public function delete(): void
    {
        $this->authorize('edit_servers');
        ServerGroup::find($this->deleteId)?->delete();
        $this->groupname = '';
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
        $this->groupId = -1;
        $this->groupname = '';
        $this->balancemethod = '';
        $this->currentServers = [];
        $this->serversSelection = [];
    }

    #[Computed]
    public function allServers(): Collection
    {
        return Server::select('id', 'servername')->get();
    }

    #[On('edit-server-group')]
    public function editServerGroup($rowId): void
    {
        $serverGroup = ServerGroup::find($rowId);
        if ($serverGroup == null) {
            session()->flash('error', 'Server Group $'.$rowId.' not found');

            return;
        }
        $this->resetInput();

        $this->groupId = $serverGroup->id;
        $this->groupname = $serverGroup->groupname;
        $this->balancemethod = $serverGroup->balancemethodtype;

        $this->currentServers = Server::whereIn('id', $serverGroup->servers)->get();
        $this->serversSelection = $this->currentServers->pluck('id')->toArray();
    }

    public function updateServerGroup(): void
    {
        $this->authorize('edit_servers');
        $validatedData = $this->validate();

        $serversSelection = array_map('intval', $validatedData['serversSelection']);

        ServerGroup::where('id', $this->groupId)->update([
            'groupname' => $validatedData['groupname'],
            'balancemethodtype' => $validatedData['balancemethod'],
            'servers' => $serversSelection,
        ]);
        session()->flash('message', 'Group Updated Successfully');
        $this->closeModal('editServerGroupModal');
        $this->refreshTable();
    }

    public function addServerGroup(): void
    {
        $this->resetInput();
    }

    public function createServerGroup(): void
    {
        $validatedData = $this->validate();

        $serversSelection = array_map('intval', $validatedData['serversSelection']);

        ServerGroup::create([
            'groupname' => $validatedData['groupname'],
            'balancemethodtype' => $validatedData['balancemethod'],
            'servers' => $serversSelection,
        ]);

        session()->flash('message', 'Successfully Added Server Group');
        $this->closeModal('addServerGroupModal');
        $this->refreshTable();
    }


    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-server-groups-table');
    }

    public function render(): View
    {
        return view('livewire.servers.show-server-groups');
    }
}
