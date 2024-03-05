<?php

namespace App\Livewire;

use App\Models\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowTags extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $tagId;

    public ?string $name;

    public ?string $tag;

    public ?string $description;

    public ?string $server;

    public string $search = '';

    protected array $rules = [
        'name' => 'required|string|exists:tags,name',
        'tag' => 'required|string',
        'description' => 'required|string',
        'server' => 'string|nullable',
    ];

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function addTag()
    {
        $this->resetInput();
    }

    public function createTag()
    {
        $this->authorize('edit_tags');
        // name has to be unique.
        $validatedData = $this->validate([
            'name' => 'required|string|unique:App\Models\Permissions\Group,name',
            'tag' => 'required|string',
            'description' => 'required|string',
            'server' => 'string|nullable',
        ]);

        $name = $validatedData['name'];
        $tag = $validatedData['tag'];
        $description = $validatedData['description'];
        $server = $validatedData['server'] ?? '';

        Tag::create([
            'name' => $name,
            'tag' => $tag,
            'description' => $description,
            'server' => $server,
        ]);
        session()->flash('message', 'Successfully Added Tag');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function editTag(Tag $tag)
    {
        $this->tagId = $tag->id;
        $this->name = $tag->name;
        $this->tag = $tag->tag;
        $this->description = $tag->description;
        $this->server = $tag->server;
    }

    public function updateTag()
    {
        $this->authorize('edit_tags');
        $validatedData = $this->validate();

        $name = $validatedData['name'];
        $tag = $validatedData['tag'];
        $description = $validatedData['description'];
        $server = $validatedData['server'] ?? '';

        Tag::where('id', $this->tagId)->update([
            'name' => $name,
            'tag' => $tag,
            'description' => $description,
            'server' => $server,
        ]);
        session()->flash('message', 'Tag Updated Successfully');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function deleteTag(Tag $tag)
    {
        $this->tagId = $tag->id;
        $this->name = $tag->name;
    }

    public function delete()
    {
        $this->authorize('edit_tags');
        Tag::find($this->tagId)->delete();
        $this->resetInput();
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->tagId = -1;
        $this->name = null;
        $this->tag = null;
        $this->description = null;
        $this->server = null;
    }

    public function render(): View
    {
        $tags = Tag::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('server', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'DESC')->paginate(10);

        return view('livewire.tags.show-tags')->with('tags', $tags);
    }
}
