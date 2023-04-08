<?php

namespace App\Http\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class Sidebar extends Component {
    public function render(): View
    {
        return view('livewire.base.sidebar');
    }
}
