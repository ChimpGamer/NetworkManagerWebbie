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
     private const GLOBAL_SERVER_NAME = 'global';
    private const ANALYTICS_PERIOD_DAYS = 30;

    /**
     * Finds a server in the server collection by name
     */
    private function findServerByName(array $serversData, string $name): ?ServerData
    {
        return collect($serversData)
            ->first(fn(ServerData $server) => $server->getName() === $name);
    }

    /**
     * Adds time series data point to server statistics
     */
    private function addTimeSeriesData(ServerData $server, int $playerCount, float $timestamp): void
    {
        $server->data[] = new TimeData($timestamp, $playerCount);
    }

    #[Computed]
    public function data(): Collection
    {
        $serversData = array();
        $analyticsData = ServerAnalytic::select('TIME', 'SERVERS', 'ONLINE')
            ->where('TIME', '>', Carbon::now()->subDays(self::ANALYTICS_PERIOD_DAYS)->getTimestampMs())
            ->orderBy('TIME')
            ->get();


        foreach ($analyticsData as $analytic) {
            $serversJson = json_decode($analytic->SERVERS, true);
            $timestamp = $analytic->TIME;

            $globalServer = $this->findServerByName($serversData, self::GLOBAL_SERVER_NAME)
                ?? $serversData[] = new ServerData(self::GLOBAL_SERVER_NAME);
            $this->addTimeSeriesData($globalServer, $analytic->ONLINE, $timestamp);

            foreach ($serversJson as $serverInfo) {
                $serverName = $serverInfo['name'];

                $server = $this->findServerByName($serversData, $serverName)
                    ?? $serversData[] = new ServerData($serverName);
                $this->addTimeSeriesData($server, $serverInfo['players'], $timestamp);
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
