<?php

namespace App\Livewire\Languages;

use App\Models\Language;
use App\Models\LanguageMessage;
use App\Models\Value;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowLanguages extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public ?string $name;

    public string $search = '';

    public int $deleteId;

    protected function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function addLanguage()
    {
        $this->resetInput();
    }

    public function createLanguage()
    {
        $validatedData = $this->validate();

        $language = Language::create($validatedData);

        $languageMessages = LanguageMessage::where('language_id', 1)->get();

        $newLanguageMessages = $languageMessages->map(function ($languageMessage) use ($language) {
            $languageMessage->id = 0;
            $languageMessage->language_id = $language->id;
            return $languageMessage;
        });

        LanguageMessage::insert($newLanguageMessages->toArray());

        session()->flash('message', 'Successfully Added Language');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->deleteId = -1;
        $this->name = null;
    }

    public function deleteLanguage(Language $language)
    {
        if ($this->isProtectedLanguage($language)) {
            session()->flash('warning-message', 'The '.$language->name.' language cannot be deleted!');
            return;
        }

        $this->deleteId = $language->id;
        $this->name = $language->name;
    }

    public function delete()
    {
        $language = Language::find($this->deleteId);
        if ($this->isProtectedLanguage($language)) {
            session()->flash('warning-message', 'The '.$language->name.' language cannot be deleted!');
            $this->resetInput();
            return;
        }

        $language->delete();
        $this->resetInput();
    }

    public function isProtectedLanguage(Language $language): bool
    {
        if ($language->id == 1) {
            return true;
        } else {
            $defaultLanguage = Language::getByName(Value::getValueByVariable('setting_language_default')->value);
            if ($defaultLanguage != null) {
                if ($language->id == $defaultLanguage->id) {
                    return true;
                }
            }
        }

        return false;
    }

    public function render(): View
    {
        $languages = Language::where('name', 'like', '%'.$this->search.'%')->orderBy('id', 'ASC')->paginate(10);

        return view('livewire.languages.show-languages')->with('languages', $languages);
    }
}
