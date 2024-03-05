<?php

namespace App\Livewire\Tickets;

use App\Models\Tickets\Ticket;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowTickets extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    protected string $paginationTheme = 'bootstrap';

    public function render(): View
    {
        $tickets = Ticket::orderBy('last_update', 'DESC')->paginate(10);

        return view('livewire.tickets.show-tickets')->with('tickets', $tickets);
    }
}
