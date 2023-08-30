<?php

namespace App\Http\Livewire;

use App\Models\HelpOP;
use Livewire\Component;
use Livewire\WithPagination;

class ShowHelpOP extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public function render()
    {
        $data = HelpOP::join('players', 'helpop.requester', '=', 'players.uuid')
            ->select('helpop.*', 'players.username')
            ->orderBy('id', 'DESC')->paginate(10);
        return view('livewire.helpop.show-helpop')->with('data', $data);
    }
}
