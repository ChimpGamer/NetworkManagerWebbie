<?php

namespace Addons\UltimateTags\Livewire;

use Addons\UltimateTags\App\Models\Tag;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowTags extends Component
{
    public int $tagId;

    public ?string $name;

    public ?string $tag;

    public ?string $description;

    public ?string $permission;

    public ?string $server;

    protected array $rules = [
        'name' => 'required|string|exists:Addons\UltimateTags\App\Models\Tag,name',
        'tag' => 'required|string',
        'description' => 'string|nullable',
        'permission' => 'string|nullable',
        'server' => 'string|nullable',
    ];

    public function addTag(): void
    {
        $this->resetInput();
    }

    public function createTag(): void
    {
        // name has to be unique.
        $validatedData = $this->validate([
            'name' => 'required|string|unique:Addons\UltimateTags\App\Models\Tag,name',
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
        $this->refreshTable();
    }

    #[On('edit')]
    public function editTag($rowId): void
    {
        $tag = Tag::find($rowId);
        if ($tag == null) {
            session()->flash('error', 'Tag #'.$rowId.' not found');

            return;
        }

        $this->tagId = $tag->id;
        $this->name = $tag->name;
        $this->tag = $tag->tag;
        $this->description = $tag->description;
        $this->permission = $tag->permission;
        $this->server = $tag->server;
    }

    public function updateTag(): void
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
        $this->refreshTable();
    }

    #[On('delete')]
    public function deleteTag($rowId): void
    {
        $tag = Tag::find($rowId);
        if ($tag == null) {
            session()->flash('error', 'Tag #'.$rowId.' not found');

            return;
        }

        $this->tagId = $tag->id;
        $this->name = $tag->name;
    }

    public function delete(): void
    {
        Tag::find($this->tagId)->delete();
        $this->closeModal('deleteTagModal');
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
        $this->tagId = -1;
        $this->name = null;
        $this->tag = null;
        $this->description = null;
        $this->permission = null;
        $this->server = null;
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-tags-table');
    }

    public function render(): View|Factory|Application
    {
        return view('ultimatetags::livewire.show-tags');
    }
}
