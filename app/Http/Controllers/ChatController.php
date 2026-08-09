<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index(): View
    {
        $this->authorize('view_chat');
        return view('chat.index');
    }
}
