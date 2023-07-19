<?php

namespace App\Http\Livewire;

use App\Models\Player;
use Illuminate\View\View;
use Livewire\Component;

class ShowPlayer extends Component
{
    public Player $player;
    public function render(): View
    {
        return view('livewire.players.show-player')->with('player', $this->player);
    }
}
