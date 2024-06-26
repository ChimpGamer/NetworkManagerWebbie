<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\View\View;

class TagsController extends Controller
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
        $this->authorize('view_tags');
        return view('tags.index');
    }
}
