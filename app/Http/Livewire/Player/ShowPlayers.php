<?php

namespace App\Http\Livewire\Player;

use App\Models\Player;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPlayers extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public function updated()
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $players = Player::where('username', 'like', '%'.$this->search.'%')
            ->orWhere('ip', '=', $this->search)
            ->orWhere('uuid', '=', $this->search)
            ->orderBy('firstlogin', 'DESC')
            ->paginate(10);

        return view('livewire.players.show-players')->with('players', $players);
    }
}
