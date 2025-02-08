<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class ShowChat extends Component
{

    public int $type = 1;

    public function render(): View|Application|Factory
    {
        return view('livewire.chat.show-chat');
    }
}
