<?php

namespace App\Livewire\Servers;

use App\Models\Server;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowServers extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    protected string $paginationTheme = 'bootstrap';

    public int $serverId;

    public string $servername;

    public string $displayname;

    public string $ip;

    public string $port;

    public ?string $motd;

    public ?string $allowed_versions = null;

    public bool $restricted;

    public bool $online;

    public string $search = '';

    public int $deleteId;

    protected function rules()
    {
        return [
            'servername' => 'required|string|min:3',
            'displayname' => 'required|string|min:3',
            'ip' => 'required|string',
            'port' => 'required|int',
            'motd' => '',
            'allowed_versions' => '',
            'restricted' => 'boolean',
        ];
    }

    public function showServer(Server $server)
    {
        $this->serverId = $server->id;
        $this->servername = $server->servername;
        $this->displayname = $server->displayname;
        $this->ip = $server->ip;
        $this->port = $server->port;
        $this->motd = $server->motd;
        if ($server->allowed_versions != null) {
            $this->allowed_versions = $server->allowed_versions;
        } else {
            $this->allowed_versions = 'All';
        }
        $this->restricted = $server->restricted;
        $this->online = $server->online;
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
        if ($fields == 'search') {
            $this->resetPage();

            return;
        }
    }

    public function editServer(Server $server)
    {
        $this->resetInput();

        $this->serverId = $server->id;
        $this->servername = $server->servername;
        $this->displayname = $server->displayname;
        $this->ip = $server->ip;
        $this->port = $server->port;
        $this->motd = $server->motd;
        $this->allowed_versions = $server->allowed_versions;
        $this->restricted = $server->restricted;
    }

    public function updateServer()
    {
        $this->authorize('edit_servers');
        $validatedData = $this->validate();

        $allowedVersions = empty($validatedData['allowed_versions']) ? null : $validatedData['allowed_versions'];

        Server::where('id', $this->serverId)->update([
            'servername' => $validatedData['servername'],
            'displayname' => $validatedData['displayname'],
            'ip' => $validatedData['ip'],
            'port' => $validatedData['port'],
            'motd' => $validatedData['motd'],
            'allowed_versions' => $allowedVersions,
            'restricted' => $validatedData['restricted'],
        ]);
        session()->flash('message', 'Server Updated Successfully');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->servername = '';
        $this->displayname = '';
        $this->ip = '';
        $this->port = '';
        $this->motd = '';
        $this->allowed_versions = null;
        $this->restricted = false;
    }

    public function deleteServer(Server $server)
    {
        $this->deleteId = $server->id;
        $this->servername = $server->servername;
    }

    public function delete()
    {
        $this->authorize('edit_servers');
        Server::find($this->deleteId)->delete();
        $this->servername = '';
    }

    public function addServer()
    {
        $this->resetInput();
    }

    public function createServer()
    {
        $validatedData = $this->validate();

        $allowedVersions = empty($validatedData['allowed_versions']) ? null : $validatedData['allowed_versions'];

        Server::create([
            'servername' => $validatedData['servername'],
            'displayname' => $validatedData['displayname'],
            'ip' => $validatedData['ip'],
            'port' => $validatedData['port'],
            'motd' => $validatedData['motd'],
            'allowed_versions' => $allowedVersions,
            'restricted' => $validatedData['restricted'],
        ]);

        session()->flash('message', 'Successfully Added Server');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function render(): View
    {
        $servers = Server::where('servername', 'like', '%'.$this->search.'%')->orderBy('id', 'ASC')->paginate(10);

        return view('livewire.servers.show-servers')->with('servers', $servers);
    }
}
