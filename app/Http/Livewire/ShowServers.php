<?php

namespace App\Http\Livewire;

use App\Models\Server;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowServers extends Component
{
    use WithPagination;

    public int $serverId;
    public string $servername, $displayname, $ip, $port;
    public ?string $motd, $allowed_versions;
    public bool $restricted, $online;
    public string $search = '';

    protected function rules()
    {
        return [
            'servername' => 'required|string|min:6',
            'displayname' => 'required|string|min:6',
            'ip' => 'required|string',
            'port' => 'required|int',
            'motd' => '',
            'allowed_versions' => '',
            'restricted' => '',
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
        $validatedData = $this->validate();

        Server::where('id', $this->serverId)->update([
            'servername' => $validatedData['servername'],
            'displayname' => $validatedData['displayname'],
            'ip' => $validatedData['ip'],
            'port' => $validatedData['port'],
            'motd' => $validatedData['motd'],
            'allowed_versions' => $validatedData['allowed_versions'],
            //'restricted' => $validatedData['restricted'],
        ]);
        session()->flash('message', 'Server Updated Successfully');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
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
        $this->allowed_versions = '';
        $this->restricted = false;
    }


    public function render(): View
    {
        $servers = Server::where('servername', 'like', '%' . $this->search . '%')->orderBy('id', 'ASC')->paginate(10);
        return view('livewire.servers.show-servers')->with('servers', $servers);
    }
}
