<?php

namespace App\Livewire;

use App\Models\Filter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowFilter extends Component
{
    use AuthorizesRequests;

    public int $filterId;

    public ?string $name;

    public ?string $description;

    public ?string $word;

    public ?string $replacement;

    public ?string $server;

    public bool $enabled;

    protected array $rules = [
        'name' => 'required|string|alpha_dash|max:128',
        'description' => 'string|nullable',
        'word' => 'required|string',
        'replacement' => 'string|nullable',
        'server' => 'string|nullable',
        'enabled' => 'required|boolean',
    ];

    #[On('info')]
    public function showFilter($rowId): void
    {
        $filter = Filter::find($rowId);
        if ($filter == null) {
            session()->flash('error', 'Filter #'.$rowId.' not found');

            return;
        }

        $this->filterId = $filter->id;
        $this->name = $filter->name;
        $this->description = $filter->description;
        $this->word = $filter->word;
        $this->replacement = $filter->replacement;
        $this->server = $filter->server;
        $this->enabled = $filter->enabled;
    }

    public function addFilter(): void
    {
        $this->resetInput();
    }

    public function createFilter(): void
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
        $this->refreshTable();
    }

    #[On('edit')]
    public function editFilter($rowId): void
    {
        $filter = Filter::find($rowId);
        if ($filter == null) {
            session()->flash('error', 'Filter #'.$rowId.' not found');

            return;
        }

        $this->filterId = $filter->id;
        $this->name = $filter->name;
        $this->description = $filter->description;
        $this->word = $filter->word;
        $this->replacement = $filter->replacement;
        $this->server = $filter->server;
        $this->enabled = $filter->enabled;
    }

    public function updateFilter(): void
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
        $this->refreshTable();
    }

    #[On('delete')]
    public function deleteFilter($rowId): void
    {
        $filter = Filter::find($rowId);
        if ($filter == null) {
            session()->flash('error', 'Filter #'.$rowId.' not found');

            return;
        }

        $this->filterId = $filter->id;
    }

    public function delete(): void
    {
        $this->authorize('edit_filter');
        Filter::find($this->filterId)?->delete();
        $this->resetInput();
        $this->refreshTable();
    }

    public function closeModal(?string $modalId = null): void
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    public function resetInput(): void
    {
        $this->filterId = -1;
        $this->name = null;
        $this->description = null;
        $this->word = null;
        $this->replacement = null;
        $this->server = null;
        $this->enabled = false;
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-filters-table');
    }

    public function render()
    {
        return view('livewire.filter.show-filter');
    }
}
