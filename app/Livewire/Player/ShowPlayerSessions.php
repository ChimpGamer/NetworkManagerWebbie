<?php

namespace App\Livewire\Player;

use App\Models\Player\Player;
use App\Models\Player\Session;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPlayerSessions extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public Player $player;

    public function render(): View
    {
        $sessions = Session::select('start', 'end', 'time', 'ip', 'version')
            ->where('uuid', $this->player->uuid)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.players.show-player-sessions')->with('sessions', $sessions);
    }
}
