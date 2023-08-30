<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    private function getTotalPlayers(): int {
        return Player::count();
    }

    private function getTodayOnlinePlayers(): int {
        $start = Carbon::today()->getTimestampMs();
        return Player::where('lastlogin', '>', $start)->count();
    }

    private function getNewPlayers(): int {
        $start = Carbon::today()->getTimestampMs();
        return Player::where('firstlogin', '>', $start)->count();
    }

    private function getTodayPlaytime(): string {
        $start = Carbon::today()->getTimestampMs();
        $data = DB::table('sessions')->select('time')->where('start', '>', $start)->get();

        $time = 0;
        foreach ($data as $item) {
            $time += $item->time;
        }
        try {
            return CarbonInterval::millisecond($time)->cascade()->forHumans(['short' => true, 'options' => 0]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home')
            ->with('total_players', $this->getTotalPlayers())
            ->with('today_online_players', $this->getTodayOnlinePlayers())
            ->with('new_players', $this->getNewPlayers())
            ->with('today_playtime', $this->getTodayPlaytime())
            ;
    }
}
