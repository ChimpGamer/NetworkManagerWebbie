<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\View\View;
use App\Models\Player;

class ShowAnalytics extends Component {

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

    public function render(): View
    {
        return view('livewire.analytics.show-analytics');
    }
}
