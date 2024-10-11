<?php

namespace App\Livewire\ServerStats;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class ShowServerStats extends Component
{

    public function render(): View|Factory|Application
    {
        return view('livewire.serverstats.show-server-stats');
    }
}
