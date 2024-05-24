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

    public ?string $name;
    public ?string $description;
    public ?string $word;

    public ?string $replacement;

    public ?string $server;

    public bool $enabled;

    public string $search = '';

    protected $rules = [
        'name' => 'required|string|alpha_dash|max:128',
        'description' => 'string|nullable',
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

    public function showFilter(Filter $filter): void
    {
        $this->filterId = $filter->id;
        $this->name = $filter->name;
        $this->description = $filter->description;
        $this->word = $filter->word;
        $this->replacement = $filter->replacement;
        $this->server = $filter->server;
        $this->enabled = $filter->enabled;
    }

    public function addFilter()
    {
        $this->resetInput();
    }

    public function createFilter()
    {
        $this->authorize('edit_filter');
        $validatedData = $this->validate();

        $description = empty($validatedData['description']) ? null : $validatedData['description'];
        $replacement = empty($validatedData['replacement']) ? null : $validatedData['replacement'];
        $server = empty($validatedData['server']) ? null : $validatedData['server'];

        Filter::create([
            'name' => $validatedData['name'],
            'description' => $description,
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
        $this->name = $filter->name;
        $this->description = $filter->description;
        $this->word = $filter->word;
        $this->replacement = $filter->replacement;
        $this->server = $filter->server;
        $this->enabled = $filter->enabled;
    }

    public function updateFilter()
    {
        $this->authorize('edit_filter');
        $validatedData = $this->validate();

        $description = empty($validatedData['description']) ? null : $validatedData['description'];
        $replacement = empty($validatedData['replacement']) ? null : $validatedData['replacement'];
        $server = empty($validatedData['server']) ? null : $validatedData['server'];

        Filter::where('id', $this->filterId)->update([
            'name' => $validatedData['name'],
            'description' => $description,
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

    public function closeModal(?string $modalId = null)
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    public function resetInput()
    {
        $this->filterId = -1;
        $this->name = null;
        $this->description = null;
        $this->word = null;
        $this->replacement = null;
        $this->server = null;
        $this->enabled = false;
    }

    public function render()
    {
        $filters = Filter::where('name', 'like', '%' . $this->search . '%')
            ->where('word', 'like', '%' . $this->search . '%')->orderBy('id', 'DESC')->paginate(10);

        return view('livewire.filter.show-filter')->with('filters', $filters);
    }
}
