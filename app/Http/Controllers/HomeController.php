<?php

namespace App\Http\Controllers;

use App\Models\Player\Player;
use App\Models\Player\Session;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
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

    private function getTotalPlayers(): int
    {
        return Player::count();
    }

    private function getTodayOnlinePlayers(): int
    {
        $start = Carbon::today()->getTimestampMs();

        return Player::where('lastlogin', '>', $start)->count();
    }

    private function getNewPlayers(): int
    {
        $start = Carbon::today()->getTimestampMs();

        return Player::where('firstlogin', '>', $start)->count();
    }

    private function getTodayPlaytime(): string
    {
        $start = Carbon::today()->getTimestampMs();
        $time = Session::select('time')->where('start', '>', $start)->sum('time');
        try {
            return CarbonInterval::millisecond($time)->cascade()->forHumans(['short' => true, 'options' => 0]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('home')
            ->with('total_players', $this->getTotalPlayers())
            ->with('today_online_players', $this->getTodayOnlinePlayers())
            ->with('new_players', $this->getNewPlayers())
            ->with('today_playtime', $this->getTodayPlaytime());
    }
}
