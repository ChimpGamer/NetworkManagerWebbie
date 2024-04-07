<?php

namespace App\Http\Controllers\Modules\Punishments;

use App\Http\Controllers\Controller;
use App\Models\Punishment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\View\View;

class PunishmentsController extends Controller
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
        $this->authorize('view_punishments');
        return view('punishments.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Punishment $punishment): View
    {
        $this->authorize('view_punishments');
        return view('punishments.show')->with('punishment', $punishment);
    }
}
