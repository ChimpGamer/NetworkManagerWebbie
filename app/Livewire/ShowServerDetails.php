<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;

class ShowServerDetails extends Component
{
    public int $serverId;

    public string $servername;

    public function showServer(int $serverId)
    {
        Log::info('showServer');
        $this->serverId = $serverId;
        //$this->servername = $server->servername;
    }

    public function render(): View
    {
        return view('livewire.servers.show-server-details');
    }
}
