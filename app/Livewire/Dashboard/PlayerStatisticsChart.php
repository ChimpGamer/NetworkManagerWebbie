<?php

namespace App\Livewire\Dashboard;

use App\Models\Player\Player;
use App\Models\Player\Session;
use App\Models\ServerAnalytic;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class PlayerStatisticsChart extends Component
{
    #[Computed]
    public function newPlayers(): array
    {
        return Player::selectRaw('cast(from_unixtime(firstlogin/1000) as date) as day, count(*) as amount')
            ->where('firstlogin', '>', now()->subDays(60)->getTimestampMs())
            ->groupBy('day')
            ->get()
            ->map(fn ($player) => [(float) strtotime($player->day) * 1000, (int) $player->amount])
            ->all();
    }

    #[Computed]
    public function sessions(): array
    {
        return Session::selectRaw('cast(from_unixtime(start/1000) as date) as day, count(*) as amount')
            ->where('start', '>', now()->subDays(60)->getTimestampMs())
            ->groupBy('day')
            ->get()
            ->map(fn ($session) => [(float) strtotime($session->day) * 1000, (int) $session->amount])
            ->all();
    }

    #[Computed]
    public function playerPeak(): array
    {
        return ServerAnalytic::selectRaw('cast(from_unixtime(time/1000) as date) as day, MAX(online) as amount')
            ->where('time', '>', now()->subDays(60)->getTimestampMs())
            ->groupBy('day')
            ->get()
            ->map(fn ($serverAnalytic) => [(float) strtotime($serverAnalytic->day) * 1000, (int) $serverAnalytic->amount])
            ->all();
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

    public function render(): View
    {
        return view('livewire.dashboard.player-statistics-chart');
    }
}
