<?php

namespace App\Http\Livewire;

use App\Models\Player;
use App\Models\Punishment;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ShowPlayerPunishments extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public Player $player;
    public function render(): View {

        $punishments = Punishment::where('uuid', $this->player->uuid)
            ->where('type', '!=', 20)
            ->where('type', '!=', 21)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('livewire.players.show-player-punishments')->with('punishments', $punishments);
    }
}
