<?php

namespace App\Livewire\Analytics;

use App\Models\Player;
use App\Models\ServerAnalytic;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShowAnalytics extends Component
{
    #[Computed]
    public function mostPlayedVersions()
    {
        return Player::getMostPlayedVersions();
    }

    #[Computed]
    public function mostUsedVirtualHosts()
    {
        return Player::getMostUsedVirtualHosts();
    }

    #[Computed]
    public function onlinePlayersData()
    {
        return ServerAnalytic::select('TIME', 'ONLINE')->where('TIME', '>', Carbon::now()->subDays(30)->getTimestampMs())
            ->orderBy('TIME')
            ->get()->map(function (ServerAnalytic $serverAnalytic) {
                return array_values($serverAnalytic->attributesToArray()); // Remove keys (no idea if there is a better way)
            });
    }

    public function render(): View
    {
        return view('livewire.analytics.show-analytics');
    }
}
