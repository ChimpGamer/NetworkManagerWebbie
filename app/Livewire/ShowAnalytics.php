<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\View\View;
use App\Models\Player;

class ShowAnalytics extends Component {

    public function render(): View
    {
        $mostPlayedVersions = Player::getMostPlayedVersions();
        $mostUsedVirtualHosts = Player::getMostUsedVirtualHosts();
        return view('livewire.analytics.show-analytics', ['mostPlayedVersions' => $mostPlayedVersions, 'mostUsedVirtualHosts' => $mostUsedVirtualHosts]);
    }
}
