<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Auth\Access\AuthorizationException;
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

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view_languages');
        return view('languages.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Language $language): View
    {
        $this->authorize('view_languages');
        return view('languages.show')->with('language', $language);
    }
}
