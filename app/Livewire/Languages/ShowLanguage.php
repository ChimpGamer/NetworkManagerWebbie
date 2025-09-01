<?php

namespace App\Livewire\Languages;

use App\Models\Language;
use App\Models\LanguageMessage;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowLanguage extends Component
{
    public Language $language;

    public int $languageMessageId;

    public ?string $languageMessageKey;

    public ?string $languageMessage;

    protected function rules(): array
    {
        return [
            'languageMessage' => 'string',
        ];
    }

    #[On('edit')]
    public function editLanguageMessage(LanguageMessage $languageMessage): void
    {
        $this->languageMessageId = $languageMessage->id;
        $this->languageMessageKey = $languageMessage->key;
        $this->languageMessage = $languageMessage->message;
    }

    public function updateLanguageMessage(): void {
        $this->authorize('edit_languages');
        $validatedData = $this->validate();


        $message = $validatedData['languageMessage'];

        LanguageMessage::where('id', $this->languageMessageId)->update([
            'language_id' => $this->language->id,
            'message' => $message,
        ]);
        session()->flash('message', 'Language Message Updated Successfully');
        $this->closeModal('editLanguageMessageModal');
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
        $this->languageMessageId = -1;
        $this->languageMessageKey = null;
        $this->languageMessage = null;
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-language-messages-table');
    }

    public function render(): View
    {
        return view('livewire.languages.show-language', ['language' => $this->language]);
    }
}
