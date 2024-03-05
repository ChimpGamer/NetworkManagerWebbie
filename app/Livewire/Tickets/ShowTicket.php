<?php

namespace App\Livewire\Tickets;

use App\Models\Tickets\Ticket;
use App\Models\Tickets\TicketPriority;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShowTicket extends Component
{
    use AuthorizesRequests;

    public Ticket $ticket;

    public array $assignOptions = [];

    public function mount(): void
    {
        $this->assignOptions = array_merge($this->assignOptions, ['Unassigned'], User::all()->map(function ($item) {
            return $item->username;
        })->toArray());
        $assigned_to = $this->ticket->assigned_to;
        if ($assigned_to != null) {
            $this->assignOptions[] = $assigned_to;
        }
    }

    #[Computed]
    public function ticketPriorityCases(): array
    {
        return TicketPriority::cases();
    }

    #[NoReturn]
    public function setPriority(&$value): void
    {
        $ticketPriority = TicketPriority::from($value);
        $this->ticket->update(['priority' => $ticketPriority, 'last_update' => Carbon::now()->getTimestampMs()]);
    }

    public function setAssigned(?string $username): void
    {
        if ($username == 'Unassigned') {
            $username = null;
        }
        $this->ticket->update(['assigned_from' => Auth::user()->username, 'assigned_to' => $username, 'last_update' => Carbon::now()->getTimestampMs()]);
    }

    public function render(): View
    {
        return view('livewire.tickets.show-ticket')->with('ticket', $this->ticket);
    }
}
