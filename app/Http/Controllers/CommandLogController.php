<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CommandLogController extends Controller
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

    public function index(): View
    {
        $this->authorize('view_command_log');
        return view('commandlog.index');
    }
}
