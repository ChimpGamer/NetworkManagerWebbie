<?php

namespace App\Livewire\Player;

use App\Models\Player\Player;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPlayers extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public int $per_page = 10;

    public function updated()
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $players = Player::where('username', 'like', '%'.$this->search.'%')
            ->orWhere('ip', 'like', '%'.$this->search.'%')
            ->orWhere('country', 'like', '%' . $this->search . '%')
            ->orWhere('uuid', '=', $this->search)
            ->orderBy('firstlogin', 'DESC')
            ->paginate($this->per_page);

        return view('livewire.players.show-players')->with('players', $players);
    }
}
