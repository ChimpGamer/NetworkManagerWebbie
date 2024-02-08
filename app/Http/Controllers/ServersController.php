<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Auth\Access\AuthorizationException;
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

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view_servers');
        return view('servers.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Server $server): View
    {
        $this->authorize('view_servers');
        return view('servers.show')->with('server', $server);
    }
}
