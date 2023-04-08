<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\View\View;

class AnnouncementsController extends Controller
{
    public function index(): View {
        return view('announcements.index');
    }

    public function show(Announcement $announcement): View {
        return view('announcements.show')->with('announcement', $announcement);
    }
}
