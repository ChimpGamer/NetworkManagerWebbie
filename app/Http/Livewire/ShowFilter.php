<?php

namespace App\Http\Livewire;

use App\Models\Filter;
use App\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ShowFilter extends Component
{
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

    //private $filters;

    public function addFilter()
    {
        $this->resetInput();
    }

    public function createFilter()
    {
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
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
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
        $this->enabled = false;
    }

    /*public function mount()
    {
        $this->filters = new Collection(Filter::orderBy('id', 'DESC')->get()->filter(function (Filter $filter) {
            return Str::of($this->search)->test('/'.$filter->word.'/');
        }));
        dd($this->filters);
    }*/

    public function render()
    {
        $filters = Filter::where('word', 'like', '%'.$this->search.'%')->orderBy('id', 'DESC')->paginate(10);

        return view('livewire.filter.show-filter')->with('filters', $filters);
    }
}
