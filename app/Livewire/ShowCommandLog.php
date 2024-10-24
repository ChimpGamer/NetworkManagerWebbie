<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class ShowCommandLog extends Component
{

    public function render(): View|Application|Factory
    {
        return view('livewire.commandlog.show-command-log');
    }
}
