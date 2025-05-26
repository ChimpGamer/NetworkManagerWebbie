<?php

namespace App\Livewire\Languages;

use App\Models\Language;
use Illuminate\View\View;
use Livewire\Component;

class ShowLanguage extends Component
{
    public Language $language;

    public function render(): View
    {
        return view('livewire.languages.show-language', ['language' => $this->language]);
    }
}
