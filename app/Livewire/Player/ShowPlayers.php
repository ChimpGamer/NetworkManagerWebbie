<?php

namespace App\Livewire\Player;

use Illuminate\View\View;
use Livewire\Component;

class ShowPlayers extends Component
{
    public function render(): View
    {
        return view('livewire.players.show-players');
    }
}
