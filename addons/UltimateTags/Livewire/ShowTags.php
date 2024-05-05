<?php

namespace Addons\UltimateTags\Livewire;

use Addons\UltimateTags\App\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;

class ShowTags extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $tagId;

    public ?string $name;

    public ?string $tag;

    public ?string $description;

    public ?string $permission;

    public ?string $server;

    public string $search = '';

    protected array $rules = [
        'name' => 'required|string|exists:Addons\UltimateTags\App\Models\Tag,name',
        'tag' => 'required|string',
        'description' => 'string|nullable',
        'permission' => 'string|nullable',
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
        // name has to be unique.
        $validatedData = $this->validate([
            'name' => 'required|string|unique:Modules\UltimateTags\App\Models\Tag,name',
            'tag' => 'required|string',
            'description' => 'string|nullable',
            'permission' => 'string|nullable',
            'server' => 'string|nullable',
        ]);

        $name = $validatedData['name'];
        $tag = $validatedData['tag'];
        $description = $validatedData['description'] ?? null;
        $permission = $validatedData['permission'] ?? 'ultimatetags.tag.default';
        $server = $validatedData['server'] ?? null;

        Tag::create([
            'name' => $name,
            'tag' => $tag,
            'description' => $description,
            'permission' => $permission,
            'server' => $server,
        ]);
        session()->flash('message', 'Successfully Added Tag');
        $this->closeModal('addTagModal');
    }

    public function editTag(Tag $tag)
    {
        $this->tagId = $tag->id;
        $this->name = $tag->name;
        $this->tag = $tag->tag;
        $this->description = $tag->description;
        $this->permission = $tag->permission;
        $this->server = $tag->server;
    }

    public function updateTag()
    {
        $validatedData = $this->validate();

        $name = $validatedData['name'];
        $tag = $validatedData['tag'];
        $description = $validatedData['description'] ?? null;
        $permission = $validatedData['permission'] ?? 'ultimatetags.tag.default';
        $server = $validatedData['server'] ?? null;

        Tag::where('id', $this->tagId)->update([
            'name' => $name,
            'tag' => $tag,
            'description' => $description,
            'permission' => $permission,
            'server' => $server,
        ]);
        session()->flash('message', 'Tag Updated Successfully');
        $this->closeModal('editTagModal');
    }

    public function deleteTag(Tag $tag)
    {
        $this->tagId = $tag->id;
        $this->name = $tag->name;
    }

    public function delete()
    {
        Tag::find($this->tagId)->delete();
        $this->closeModal('deleteTagModal');
    }

    public function closeModal($modalId)
    {
        $this->resetInput();
        $this->dispatch('close-modal', $modalId);
    }

    public function resetInput()
    {
        $this->tagId = -1;
        $this->name = null;
        $this->tag = null;
        $this->description = null;
        $this->permission = null;
        $this->server = null;
    }

    public function render()
    {
        $tags = Tag::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('server', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'DESC')->paginate(10);

        return view('ultimatetags::livewire.show-tags')->with('tags', $tags);
    }
}
