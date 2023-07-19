<?php

namespace App\Http\Controllers;

use App\Models\Player;
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

    public function index(): View {
        return view('players.index');
    }

    public function show(Player $player): View {
        return view('players.show')->with('player', $player);
    }
}
