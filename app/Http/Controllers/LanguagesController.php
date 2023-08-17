<?php

namespace App\Http\Controllers;

use App\Models\Language;
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

    public function show(Language $language): View {
        return view('languages.show')->with('language', $language);
    }
}
