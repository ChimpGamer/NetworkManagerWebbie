<?php

namespace App\Http\Controllers;

use App\Models\PunishmentTemplate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\View\View;

class PunishmentTemplatesController extends Controller
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
        $this->authorize('view_pre_punishments');
        return view('punishment_templates.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function show(PunishmentTemplate $punishmentTemplate): View
    {
        $this->authorize('view_pre_punishments');
        return view('punishment_templates.show')->with('punishmentTemplate', $punishmentTemplate);
    }
}
