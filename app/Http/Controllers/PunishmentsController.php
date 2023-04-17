<?php

namespace App\Http\Controllers;

use App\Models\Punishment;
use Illuminate\View\View;

class PunishmentsController extends Controller
{
    public function index(): View {
        return view('punishments.index');
    }

    public function show(Punishment $punishment): View {
        return view('punishments.show')->with('punishment', $punishment);
    }
}
