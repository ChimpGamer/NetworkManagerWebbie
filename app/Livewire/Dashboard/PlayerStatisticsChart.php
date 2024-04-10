<?php

namespace App\Livewire\Dashboard;

use App\Models\Player\Player;
use App\Models\Player\Session;
use App\Models\ServerAnalytic;
use Carbon\Carbon;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class PlayerStatisticsChart extends Component
{
    public $newPlayers;

    public $sessions;

    public $playerPeak;

    public function mount()
    {
        $this->newPlayers = Player::selectRaw('cast(from_unixtime(firstlogin/1000) as date) as day, count(*) as amount')
            ->where('firstlogin', '>', Carbon::now()->subDays(60)->getTimestampMs())->groupBy('day')->get()
            ->map(function (Player $player) {
                return [(float) strtotime($player->day) * 1000, (int) $player->amount];
            });

        $this->sessions = Session::selectRaw('cast(from_unixtime(start/1000) as date) as day, count(*) as amount')
            ->where('start', '>', Carbon::now()->subDays(60)->getTimestampMs())->groupBy('day')->get()
            ->map(function (Session $session) {
                return [(float) strtotime($session->day) * 1000, (int) $session->amount];
            });

        $this->playerPeak = ServerAnalytic::selectRaw('cast(from_unixtime(time/1000) as date) as day, MAX(online) as amount')
            ->where('time', '>', Carbon::now()->subDays(60)->getTimestampMs())->groupBy('day')->get()
            ->map(function (ServerAnalytic $serverAnalytic) {
                return [(float) strtotime($serverAnalytic->day) * 1000, (int) $serverAnalytic->amount];
            });
    }

    public function placeholder()
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

    public function render()
    {
        return view('livewire.dashboard.player-statistics-chart');
    }
}
