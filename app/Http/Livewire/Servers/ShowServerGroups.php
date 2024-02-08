<?php

namespace App\Http\Livewire\Servers;

use App\Models\Server;
use App\Models\ServerGroup;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowServerGroups extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public int $groupId;

    public string $groupName;

    public string $balanceMethod;

    public array $servers = [];

    public int $deleteId;

    public function showServerGroup(ServerGroup $serverGroup)
    {
        $this->groupId = $serverGroup->id;
        $this->groupName = $serverGroup->groupname;
        $this->balanceMethod = $serverGroup->balancemethodtype;
        $this->servers = [];

        $serversRaw = $serverGroup->servers;
        $serversJson = json_decode($serversRaw);
        foreach ($serversJson as $serverId) {
            $server = Server::find($serverId);
            if ($server != null) {
                $this->servers[] = $server->servername;
            }
        }
    }

    public function deleteServerGroup(ServerGroup $serverGroup)
    {
        $this->deleteId = $serverGroup->id;
        $this->groupName = $serverGroup->groupname;
    }

    public function delete()
    {
        $this->authorize('edit_servers');
        ServerGroup::find($this->deleteId)->delete();
        $this->groupName = '';
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->groupId = -1;
        $this->groupName = '';
        $this->balanceMethod = '';
        $this->servers = [];
    }

    public function render(): View
    {
        $serverGroups = ServerGroup::where('groupname', 'like', '%'.$this->search.'%')->orderBy('id', 'ASC')->paginate(10);

        return view('livewire.servers.show-server-groups')->with('servergroups', $serverGroups);
    }
}
