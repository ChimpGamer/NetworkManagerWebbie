<?php

namespace App\Http\Livewire\Languages;

use App\Models\Language;
use App\Models\LanguageMessage;
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
        $this->dispatchBrowserEvent('close-modal');
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
        $this->deleteId = $language->id;
        $this->name = $language->name;
    }

    public function delete()
    {
        if ($this->deleteId == 1) {
            session()->flash('warning-message', 'The '.$this->name.' language cannot be deleted!');
            $this->name = '';

            return;
        }
        Language::find($this->deleteId)->delete();
        $this->name = '';
    }

    public function render(): View
    {
        $languages = Language::where('name', 'like', '%'.$this->search.'%')->orderBy('id', 'ASC')->paginate(10);

        return view('livewire.languages.show-languages')->with('languages', $languages);
    }
}
