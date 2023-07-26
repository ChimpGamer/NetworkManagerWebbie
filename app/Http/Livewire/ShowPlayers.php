<?php

namespace App\Http\Livewire;

use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPlayers extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';
    public string $search = '';



    public function render(): View
    {
        $players = Player::where('username', 'like', '%' . $this->search . '%')
            ->orWhere('ip', '=', $this->search)
            ->orderBy('firstlogin', 'DESC')
            ->paginate(10);
        return view('livewire.players.show-players')->with('players', $players);
    }
}
