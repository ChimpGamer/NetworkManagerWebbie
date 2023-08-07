<?php

namespace App\Http\Livewire;

use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowLanguages extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $name;
    public string $search = '';
    public int $deleteId;

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->deleteId = -1;
    }

    public function deleteLanguage(Language $language)
    {
        $this->deleteId = $language->id;
        $this->name = $language->name;
    }

    public function delete()
    {
        if ($this->deleteId == 1) {
            session()->flash('warning-message', 'The ' . $this->name . ' language cannot be deleted!');
            $this->name = '';
            return;
        }
        Language::find($this->deleteId)->delete();
        $this->name = '';
    }

    public function render(): View
    {
        $languages = Language::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'ASC')->paginate(10);
        return view('livewire.languages.show-languages')->with('languages', $languages);
    }
}
