<?php

namespace App\Livewire\Servers;

use App\Models\Server;
use App\Models\ServerGroup;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowServerGroups extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public int $groupId;

    public string $groupname;

    public array $balancemethods = self::BALANCE_METHODS;

    public string $balancemethod;

    public $servers = [];

    public $currentServers = [];

    public $serversSelection = [];

    public int $deleteId;

    const BALANCE_METHODS = ['RANDOM', 'RANDOM_LOWEST', 'RANDOM_FILLER'];

    protected function rules()
    {
        return [
            'groupname' => 'required|string|min:3',
            'balancemethod' => 'required|string|in:'.implode(',', self::BALANCE_METHODS),
            'serversSelection' => 'required|array',
            'serversSelection.*' => 'integer|exists:servers,id',
        ];
    }

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function showServerGroup(ServerGroup $serverGroup)
    {
        $this->groupId = $serverGroup->id;
        $this->groupname = $serverGroup->groupname;
        $this->balancemethod = $serverGroup->balancemethodtype;

        $this->servers = Server::whereIn('id', $serverGroup->servers)->get();
        $this->currentServers = $this->servers;
    }

    public function deleteServerGroup(ServerGroup $serverGroup)
    {
        $this->deleteId = $serverGroup->id;
        $this->groupname = $serverGroup->groupname;
    }

    public function delete()
    {
        $this->authorize('edit_servers');
        ServerGroup::find($this->deleteId)->delete();
        $this->groupname = '';
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->groupId = -1;
        $this->groupname = '';
        $this->balancemethod = '';
        $this->servers = [];
    }

    public function getServersData()
    {
        return Server::select('id', 'servername', 'displayname')->get();
    }

    public function editServerGroup(ServerGroup $serverGroup)
    {
        $this->resetInput();

        $this->groupId = $serverGroup->id;
        $this->groupname = $serverGroup->groupname;
        $this->balancemethod = $serverGroup->balancemethodtype;

        $this->servers = $this->getServersData();
        $this->currentServers = Server::whereIn('id', $serverGroup->servers)->get();
        $this->serversSelection = $this->currentServers->pluck('id')->toArray();
    }

    public function updateServerGroup()
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
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function addServerGroup()
    {
        $this->resetInput();

        $this->servers = $this->getServersData();
    }

    public function createServerGroup()
    {
        $validatedData = $this->validate();

        $serversSelection = array_map('intval', $validatedData['serversSelection']);

        ServerGroup::create([
            'groupname' => $validatedData['groupname'],
            'balancemethodtype' => $validatedData['balancemethod'],
            'servers' => $serversSelection,
        ]);

        session()->flash('message', 'Successfully Added Server Group');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function render(): View
    {
        $serverGroups = ServerGroup::where('groupname', 'like', '%'.$this->search.'%')->orderBy('id', 'ASC')->paginate(10);

        return view('livewire.servers.show-server-groups')->with('servergroups', $serverGroups);
    }
}
