<?php

namespace App\Livewire\ServerStats;

use App\Models\ServerAnalytic;
use App\Models\ServerStats\ServerData;
use App\Models\ServerStats\TimeData;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class AdvancedServerAnalytics extends Component
{
     private const GLOBAL_SERVER_NAME = 'global';
    private const ANALYTICS_PERIOD_DAYS = 30;

    /**
     * Adds time series data point to server statistics
     */
    private function addTimeSeriesData(ServerData $server, int $playerCount, float $timestamp): void
    {
        $server->data[] = new TimeData($timestamp, $playerCount);
    }

    #[Computed]
    public function data(): array
    {
        $servers = [];

        $analytics = ServerAnalytic::query()
            ->select(['TIME', 'SERVERS', 'ONLINE'])
            ->where(
                'TIME',
                '>',
                now()->subDays(self::ANALYTICS_PERIOD_DAYS)->getTimestampMs()
            )
            ->orderBy('TIME')
            ->get();

        foreach ($analytics as $analytic) {
            $timestamp = $analytic->TIME;

            $globalServer = $servers[self::GLOBAL_SERVER_NAME]
                ??= new ServerData(self::GLOBAL_SERVER_NAME);

            $this->addTimeSeriesData(
                $globalServer,
                $analytic->ONLINE,
                $timestamp
            );

            foreach (json_decode($analytic->SERVERS, true) as $serverInfo) {
                $serverName = $serverInfo['name'];

                $server = $servers[$serverName]
                    ??= new ServerData($serverName);

                $this->addTimeSeriesData(
                    $server,
                    $serverInfo['players'],
                    $timestamp
                );
            }
        }

        return array_values($servers);
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
