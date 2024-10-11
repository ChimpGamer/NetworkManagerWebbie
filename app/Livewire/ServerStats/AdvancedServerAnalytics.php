<?php

namespace App\Livewire\ServerStats;

use App\Models\ServerAnalytic;
use App\Models\ServerStats\ServerData;
use App\Models\ServerStats\TimeData;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class AdvancedServerAnalytics extends Component
{

    function getServerData(array $serversData, string $name): ?ServerData
    {
        foreach ($serversData as $server) {
            if ($server->getName() == $name) {
                return $server;
            }
        }
        return null;
    }

    function addData(ServerData $server, int $count, float $time): void
    {
        $server->data[] = new TimeData($time, $count);
    }

    #[Computed]
    public function data(): Collection
    {
        $serversData = array();
        $res = ServerAnalytic::select('TIME', 'SERVERS', 'ONLINE')->where('TIME', '>', Carbon::now()->subDays(30)->getTimestampMs())
            ->orderBy('TIME')
            ->get();

        foreach ($res as $item) {
            $serversJson = json_decode($item->SERVERS, true);
            $time = $item->TIME;
            $globalServerData = $this->getServerData($serversData, 'global');
            if ($globalServerData == null) {
                $globalServerData = new ServerData('global');
                $serversData[] = $globalServerData;
            }
            $this->addData($globalServerData, $item->ONLINE, $time);

            foreach ($serversJson as $server) {
                $serverName = $server['name'];
                $serverData = $this->getServerData($serversData, $serverName);
                if ($serverData == null) {
                    $serverData = new ServerData($serverName);
                    $serversData[] = $serverData;
                }
                $this->addData($serverData, $server['players'], $time);
            }
        }

        return collect($serversData); // For some weird reason it doesn't work properly when returned as array or json...
    }

    public function placeholder(): string
    {
        return <<<'HTML'
            <div>
                <div class="w-100 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        HTML;
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.serverstats.advanced-server-analytics');
    }
}
