<?php

namespace App\Livewire\Servers;

use App\Models\Server;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowServers extends Component
{
    use AuthorizesRequests;

    public int $serverId;

    public string $servername;

    public string $displayname;

    public string $ip;

    public string $port;

    public ?string $motd;

    public ?string $allowed_versions = null;

    public bool $restricted;

    public bool $online;

    public int $deleteId;

    protected function rules(): array
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

    #[On('info')]
    public function showServer($rowId): void
    {
        $server = Server::find($rowId);
        if ($server == null) {
            session()->flash('error', 'Server $'.$rowId.' not found');

            return;
        }

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

    public function editServer($rowId): void
    {
        $server = Server::find($rowId);
        if ($server == null) {
            session()->flash('error', 'Server $'.$rowId.' not found');

            return;
        }

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

    public function updateServer(): void
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
        $this->closeModal('editServerModal');
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
        $this->servername = '';
        $this->displayname = '';
        $this->ip = '';
        $this->port = '';
        $this->motd = '';
        $this->allowed_versions = null;
        $this->restricted = false;
    }

    public function deleteServer($rowId): void
    {
        $server = Server::find($rowId);
        if ($server == null) {
            session()->flash('error', 'Server $'.$rowId.' not found');

            return;
        }

        $this->deleteId = $server->id;
        $this->servername = $server->servername;
    }

    public function delete(): void
    {
        $this->authorize('edit_servers');
        Server::find($this->deleteId)?->delete();
        $this->servername = '';
        $this->refreshTable();
    }

    public function addServer(): void
    {
        $this->resetInput();
    }

    public function createServer(): void
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
        $this->closeModal('addServerModal');
        $this->refreshTable();
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-servers-table');
    }

    public function render(): View
    {
        return view('livewire.servers.show-servers');
    }
}
