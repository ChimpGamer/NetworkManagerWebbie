<?php

namespace App\Livewire\Tags;

use App\Models\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowTags extends Component
{
    use AuthorizesRequests;

    public int $tagId;

    public ?string $name;

    public ?string $tag;

    public ?string $description;

    public ?string $server;

    protected array $rules = [
        'name' => 'required|string|exists:tags,name',
        'tag' => 'required|string',
        'description' => 'required|string',
        'server' => 'string|nullable',
    ];

    public function addTag(): void
    {
        $this->resetInput();
    }

    public function createTag(): void
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
        $this->closeModal('addTagModal');
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
        $this->server = $tag->server;
    }

    public function updateTag(): void
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
        $this->closeModal('editTagModal');
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
        $this->authorize('edit_tags');
        Tag::find($this->tagId)?->delete();
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
        $this->server = null;
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-tags-table');
    }

    public function render(): View
    {
        return view('livewire.tags.show-tags');
    }
}
