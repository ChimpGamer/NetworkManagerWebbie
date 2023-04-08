<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\View\View;

class ServersController extends Controller
{
    public function index(): View {
        return view('servers.index');
    }

    public function show(Server $server): View {
        return view('servers.show')->with('server', $server);
    }
}
