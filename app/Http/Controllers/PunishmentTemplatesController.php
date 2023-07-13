<?php

namespace App\Http\Controllers;

use App\Models\PunishmentTemplate;
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

    public function index(): View {
        return view('punishment_templates.index');
    }

    public function show(PunishmentTemplate $punishmentTemplate): View {
        return view('punishment_templates.show')->with('punishmentTemplate', $punishmentTemplate);
    }
}
