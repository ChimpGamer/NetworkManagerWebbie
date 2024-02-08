<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\View\View;

class PlayersController extends Controller
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
        $this->authorize('view_players');
        return view('players.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Player $player): View
    {
        $this->authorize('view_players');
        return view('players.show')->with('player', $player);
    }
}
