<?php

namespace App\Livewire\Languages;

use App\Models\Language;
use App\Models\LanguageMessage;
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

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function mount()
    {
        $this->languageMessages = LanguageMessage::select('id', 'key', 'message')
            ->where('language_id', $this->language->id)
            ->get();
    }

    public function save()
    {
        $this->authorize('edit_languages');
        $this->validate();

        $changedMessages = $this->languageMessages->filter(function ($message) {
            return $message->isDirty();
        })->values();
        if ($changedMessages->isEmpty()) {
            return;
        }
        foreach ($changedMessages as $message) {
            $message->save();
        }
        $message = 'Successfully updated the following message(s): '.$changedMessages->implode('key', ', ');
        session()->flash('message', $message);
    }

    public function render(): View
    {
        return view('livewire.languages.show-language')->with('language', $this->language)->with('messages', $this->languageMessages);
    }
}
