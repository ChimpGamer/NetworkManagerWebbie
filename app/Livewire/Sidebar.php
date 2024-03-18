<?php

namespace App\Livewire;

use App\Models\Value;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Sidebar extends Component
{

    #[Computed]
    public function isModuleEnabled(string $moduleName): bool
    {
        return Value::where('variable', $moduleName)->where('value', 1)->exists();
    }

    public function render(): View
    {
        return view('livewire.app.sidebar');
    }
}
