<?php

namespace App\Http\Livewire;

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

    public function closeModal()
    {
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
            ->orderBy('id', 'DESC')->paginate(10);

        return view('livewire.helpop.show-helpop')->with('data', $data);
    }
}
