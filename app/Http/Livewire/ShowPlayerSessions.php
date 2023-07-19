<?php

namespace App\Http\Livewire;

use App\Models\Player;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ShowPlayerSessions extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public Player $player;
    public function render(): View {
        $sessions = DB::table('sessions')
            ->where('uuid', $this->player->uuid)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('livewire.players.show-player-sessions')->with('sessions', $sessions);
    }
}
