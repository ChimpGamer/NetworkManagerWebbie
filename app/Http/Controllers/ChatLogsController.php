<?php

namespace App\Http\Controllers;

use App\Models\Chat\ChatLog;
use Faker\Core\Uuid;
use Illuminate\View\View;

class ChatLogsController extends Controller
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
        $this->authorize('view_chatlogs');

        return view('chatlogs.index');
    }

    public function show(string $uuid): View
    {
        $chatLog = ChatLog::find($uuid); // Shitty work around. For some reason ChatLog $chatLog didn't work as parameters.
        if ($chatLog === null) {
            abort(404);
        }
        $this->authorize('view_chatlogs');

        return view('chatlogs.show', ['chatLog' => $chatLog]);
    }
}
