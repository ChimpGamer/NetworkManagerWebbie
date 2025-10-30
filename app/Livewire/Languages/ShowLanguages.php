<?php

namespace App\Livewire\Languages;

use App\Models\Language;
use App\Models\LanguageMessage;
use App\Models\Value;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowLanguages extends Component
{

    public ?string $name;

    public int $deleteId;

    public ?Language $defaultLanguage = null;

    public string $key;

    public string $plugin;

    public string $version;

    protected function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    protected array $messageRules = [
        'key' => 'required|string|unique:language_messages,key|max:64',
        'plugin' => 'required|string|max:36',
        'version' => 'required|string|max:8',
    ];

    public function mount(): void
    {
        $this->defaultLanguage = Language::getByName(Value::getValueByVariable('setting_language_default')->value);
    }

    public function addLanguage()
    {
        $this->resetInput();
    }

    public function createLanguage(): void
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
        $this->closeModal('addLanguageModal');
        $this->refreshTable();
    }

    public function closeModal(?string $modalId = null): void
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    private function resetInput(): void
    {
        $this->deleteId = -1;
        $this->name = null;
    }

    #[On('delete')]
    public function deleteLanguage(Language $language): void
    {
        if ($this->isProtectedLanguage($language)) {
            session()->flash('warning-message', 'The '.$language->name.' language cannot be deleted!');

            return;
        }

        $this->deleteId = $language->id;
        $this->name = $language->name;
    }

    public function delete(): void
    {
        $language = Language::find($this->deleteId);
        if ($this->isProtectedLanguage($language)) {
            session()->flash('warning-message', 'The '.$language->name.' language cannot be deleted!');
            $this->resetInput();

            return;
        }

        $language->delete();
        $this->closeModal('deleteLanguageModal');
        $this->refreshTable();
    }

    public function addLanguageMessage(): void
    {
        $validatedData = $this->validate($this->messageRules);

        foreach (Language::all() as $language) {
            LanguageMessage::insert([
                'language_id' => $language->id,
                'key' => $validatedData['key'],
                'message' => '',
                'plugin' => $validatedData['plugin'],
                'version' => $validatedData['version'],
            ]);
        }

        session()->flash('message', 'Successfully Added Language Message! Make sure to edit your message for each language.');
        $this->closeModal('addLanguageMessageModal');
    }

    public function isProtectedLanguage(Language $language): bool
    {
        if ($language->id === 1) {
            return true;
        }

        return $this->defaultLanguage?->id == $language->id;
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-languages-table');
    }

    public function render(): View
    {
        return view('livewire.languages.show-languages');
    }
}
