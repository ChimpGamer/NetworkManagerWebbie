<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class ShowSettings extends Component
{

    public string $search = '';

    public function render(): View
    {
        $values = DB::table('values')
            ->select('variable', 'value')
            ->where('variable', 'like', '%' . $this->search . '%')
            ->orderBy('variable')
            ->get();
        return view('livewire.settings.show-settings')->with('settings', $values);
    }
}
