<?php

namespace App\Livewire\Languages;

use App\Models\Language;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class ShowLanguage extends Component
{
    public Language $language;

    public Collection $languageMessages;

    public string $search = '';

    protected function rules(): array
    {
        return [
            'languageMessages.*.key' => 'required|string',
            'languageMessages.*.message' => 'string',
        ];
    }

    public function mount(): void
    {
        $this->languageMessages = $this->language->languageMessages;
    }

    public function save(): void
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
