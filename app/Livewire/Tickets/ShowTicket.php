<?php

namespace App\Livewire\Tickets;

use App\Models\Tickets\Ticket;
use App\Models\Tickets\TicketMessage;
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

    public string $message;

    protected $rules = [
        'message' => 'required|string',
    ];

    public function mount(): void
    {
        $this->assignOptions = array_merge($this->assignOptions, ['Unassigned'], User::all()->map(function ($item) {
            return $item->username;
        })->toArray());
        $assigned_to = $this->ticket->assigned_to;
        if ($assigned_to != null && ! in_array($assigned_to, $this->assignOptions)) {
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

    public function sendMessage(): void
    {
        $validatedData = $this->validate();
        $message = $validatedData['message'];
        $uuid = Auth::check() ? Auth::user()->getUUID() : null;
        if ($uuid == null) {
            session()->flash('error', "Your username doesn't seem to be a mc username or hasn't joined the server yet.");

            return;
        }
        TicketMessage::create([
            'ticket_id' => $this->ticket->id,
            'message' => $message,
            'uuid' => $uuid,
            'time' => Carbon::now()->getTimestampMs(),
        ]);
        $this->ticket->update([
            'last_answer' => $uuid,
            'last_update' => Carbon::now()->getTimestampMs(),
            'closed_on' => null,
            'closed_by' => null,
            'active' => true,
        ]);
        $this->ticket->refresh();
    }

    public function closeTicket(): void
    {
        $uuid = Auth::check() ? Auth::user()->getUUID() : null;
        if ($uuid == null) {
            session()->flash('error', "Your username doesn't seem to be a mc username or hasn't joined the server yet.");

            return;
        }
        $this->ticket->update([
            'last_update' => Carbon::now()->getTimestampMs(),
            'closed_on' => Carbon::now()->getTimestampMs(),
            'closed_by' => $uuid,
            'active' => false,
        ]);
        $this->ticket->refresh();
        session()->flash('message', __('ticket.notifications.closed-successfully', ['ticketId' => $this->ticket->id]));
    }

    public function render(): View
    {
        return view('livewire.tickets.show-ticket')->with('ticket', $this->ticket);
    }
}
