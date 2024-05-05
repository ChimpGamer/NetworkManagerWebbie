<?php

namespace App\Livewire;

use App\Models\Filter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ShowFilter extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $filterId;

    public ?string $word;

    public ?string $replacement;

    public ?string $server;

    public bool $enabled;

    public string $search = '';

    protected $rules = [
        'word' => 'required|string',
        'replacement' => 'string|nullable',
        'server' => 'string|nullable',
        'enabled' => 'required|boolean',
    ];

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function addFilter()
    {
        $this->resetInput();
    }

    public function createFilter()
    {
        $this->authorize('edit_filter');
        $validatedData = $this->validate();

        $replacement = empty($validatedData['replacement']) ? null : $validatedData['replacement'];
        $server = empty($validatedData['server']) ? null : $validatedData['server'];

        Filter::create([
            'word' => $validatedData['word'],
            'replacement' => $replacement,
            'server' => $server,
            'enabled' => $validatedData['enabled'],
        ]);
        session()->flash('message', 'Successfully Added Filter');
        $this->closeModal('addFilterModal');
    }

    public function editFilter(Filter $filter)
    {
        $this->filterId = $filter->id;
        $this->word = $filter->word;
        $this->replacement = $filter->replacement;
        $this->server = $filter->server;
        $this->enabled = $filter->enabled;
    }

    public function updateFilter()
    {
        $this->authorize('edit_filter');
        $validatedData = $this->validate();

        $replacement = empty($validatedData['replacement']) ? null : $validatedData['replacement'];
        $server = empty($validatedData['server']) ? null : $validatedData['server'];

        Filter::where('id', $this->filterId)->update([
            'word' => $validatedData['word'],
            'replacement' => $replacement,
            'server' => $server,
            'enabled' => $validatedData['enabled'],
        ]);
        session()->flash('message', 'Filter Updated Successfully');
        $this->closeModal('editFilterModal');
    }

    public function deleteFilter(Filter $filter)
    {
        $this->filterId = $filter->id;
    }

    public function delete()
    {
        $this->authorize('edit_filter');
        Filter::find($this->filterId)->delete();
        $this->resetInput();
    }

    public function closeModal($modalId)
    {
        $this->resetInput();
        $this->dispatch('closeModal', $modalId);
    }

    public function resetInput()
    {
        $this->filterId = -1;
        $this->word = null;
        $this->replacement = null;
        $this->server = null;
        $this->enabled = false;
    }

    public function render()
    {
        $filters = Filter::where('word', 'like', '%'.$this->search.'%')->orderBy('id', 'DESC')->paginate(10);

        return view('livewire.filter.show-filter')->with('filters', $filters);
    }
}
