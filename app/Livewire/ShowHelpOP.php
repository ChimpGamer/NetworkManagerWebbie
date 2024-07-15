<?php

namespace App\Livewire;

use App\Models\HelpOP;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ShowHelpOP extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $helpOPId;

    public string $search = '';

    public int $per_page = 10;

    public function deleteHelpOP(HelpOP $helpOP)
    {
        $this->helpOPId = $helpOP->id;
    }

    public function delete()
    {
        $this->authorize('edit_helpop');
        HelpOP::find($this->helpOPId)->delete();
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->helpOPId = -1;
    }

    public function render()
    {
        $data = HelpOP::join('players', 'helpop.requester', '=', 'players.uuid')
            ->select('helpop.*', 'players.username')
            ->where('username', 'like', '%'.$this->search.'%')
            ->orWhere('message', 'like', '%'.$this->search.'%')
            ->orWhere('server', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'DESC')->paginate($this->per_page);

        return view('livewire.helpop.show-helpop')->with('data', $data);
    }
}
