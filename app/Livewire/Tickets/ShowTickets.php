<?php

namespace App\Livewire\Tickets;

use App\Models\Tickets\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowTickets extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public int $per_page = 10;

    public function render(): View
    {
        $tickets = Ticket::where(function (Builder $query) {
            $query->where('title', 'like', '%'.$this->search.'%')
                ->orWhere('message', 'like', '%'.$this->search.'%');
        })
            ->orderBy('last_update', 'DESC')
            ->paginate($this->per_page);

        return view('livewire.tickets.show-tickets')->with('tickets', $tickets);
    }
}
