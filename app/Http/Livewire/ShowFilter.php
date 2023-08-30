<?php

namespace App\Http\Livewire;

use App\Models\Filter;
use Livewire\Component;
use Livewire\WithPagination;

class ShowFilter extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $filterId;
    public ?string $word, $replacement, $server;

    protected $rules = [
        'word' => 'required|string',
        'replacement' => 'string|nullable',
        'server' => 'string|nullable',
    ];

    public function addFilter() {
        $this->resetInput();
    }

    public function createFilter() {
        $validatedData = $this->validate();

        $replacement = empty($validatedData['replacement']) ? null : $validatedData['replacement'];
        $server = empty($validatedData['server']) ? null : $validatedData['server'];

        Filter::create([
            'word' => $validatedData['word'],
            'replacement' => $replacement,
            'server' => $server,
        ]);
        session()->flash('message', 'Successfully Added Filter');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function editFilter(Filter $filter)
    {
        $this->filterId = $filter->id;
        $this->word = $filter->word;
        $this->replacement = $filter->replacement;
        $this->server = $filter->server;
    }

    public function updateFilter()
    {
        $validatedData = $this->validate();

        $replacement = empty($validatedData['replacement']) ? null : $validatedData['replacement'];
        $server = empty($validatedData['server']) ? null : $validatedData['server'];

        Filter::where('id', $this->filterId)->update([
            'word' => $validatedData['word'],
            'replacement' => $replacement,
            'server' => $server,
        ]);
        session()->flash('message', 'Filter Updated Successfully');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteFilter(Filter $filter)
    {
        $this->filterId = $filter->id;
    }

    public function delete()
    {
        Filter::find($this->filterId)->delete();
        $this->resetInput();
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->filterId = -1;
        $this->word = null;
        $this->replacement = null;
        $this->server = null;
    }

    public function render()
    {
        $filters = Filter::orderBy('id', 'DESC')->paginate(10);
        return view('livewire.filter.show-filter')->with('filters', $filters);
    }
}