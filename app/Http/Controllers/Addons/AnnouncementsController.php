<?php

namespace App\Http\Controllers\Addons;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\View\View;

class AnnouncementsController extends Controller
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
        $this->authorize('view_announcements');
        return view('announcements.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Announcement $announcement): View
    {
        $this->authorize('view_announcements');
        return view('announcements.show')->with('announcement', $announcement);
    }
}
