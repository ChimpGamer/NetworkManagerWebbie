<?php

namespace App\Http\Livewire\Languages;

use App\Models\Language;
use App\Models\LanguageMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Livewire\Component;

class ShowLanguage extends Component
{

    public Language $language;
    public $languageMessages;
    public string $search = '';

    protected function rules()
    {
        return [
            'languageMessages.*.key' => 'required|string',
            'languageMessages.*.message' => 'string',
        ];
    }

    public function mount()
    {
        $this->languageMessages = LanguageMessage::select('id', 'key', 'message')
            ->where('language_id', $this->language->id)
            ->get();
    }

    public function save()
    {
        $this->validate();

        foreach ($this->languageMessages as $message) {
            if ($message->wasChanged()){
                $message->save();
            }
        }
    }

    public function render(): View
    {
        return view('livewire.languages.show-language')->with('language', $this->language)->with('messages', $this->languageMessages);
    }
}
