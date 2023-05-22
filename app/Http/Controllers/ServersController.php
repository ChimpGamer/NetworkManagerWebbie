<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\View\View;

class ServersController extends Controller
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
        return view('servers.index');
    }

    public function show(Server $server): View {
        return view('servers.show')->with('server', $server);
    }
}
