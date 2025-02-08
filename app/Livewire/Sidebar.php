<?php

namespace App\Livewire;

use App\Models\Value;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Sidebar extends Component
{
    public Collection $values;

    public function mount(): void
    {
        $this->values = Value::where('value', 1)->get();
    }

    #[Computed]
    public function isModuleEnabled(string $moduleName): bool
    {
        return $this->values->where('variable', $moduleName)->isNotEmpty();
    }

    public function render(): View
    {
        return view('livewire.app.sidebar');
    }
}
