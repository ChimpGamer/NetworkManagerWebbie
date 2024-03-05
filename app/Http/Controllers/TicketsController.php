<?php

namespace App\Http\Controllers;

use App\Models\Tickets\Ticket;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\View\View;

class TicketsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view_tickets');
        return view('tickets.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function ticket(Ticket $ticket): View
    {
        $this->authorize('view_ticket');
        return view('tickets.view-ticket', ['ticket' => $ticket]);
    }
}
