<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LanguagesController extends Controller
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
        return view('languages.index');
    }
}
