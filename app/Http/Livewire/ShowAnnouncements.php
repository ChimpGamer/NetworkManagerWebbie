<?php

namespace App\Http\Livewire;

use App\Models\Announcement;
use App\Models\AnnouncementType;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAnnouncements extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $announcementId, $type;
    public ?string $message, $sound, $server, $condition;
    public ?string $expires;
    public bool $permission, $active, $isGlobal;

    public ?string $typeName;

    public string $search = '';
    public int $deleteId;

    protected function rules()
    {
        return [
            'type' => 'required|integer',
            'message' => 'required|string|max:512',
            'sound' => 'string|nullable',
            'server' => 'string|nullable',
            'condition' => 'string|nullable',
            'expires' => 'date|nullable',
            'permission' => 'required|boolean',
            'active' => 'required|boolean'
        ];
    }

    public function showAnnouncement(Announcement $announcement)
    {
        $this->announcementId = $announcement->id;
        $this->type = $announcement->type->value;
        $this->message = $announcement->message;
        $this->sound = $announcement->sound;
        $this->server = $announcement->server;
        $this->condition = $announcement->condition;
        $this->expires = $announcement->expires;

        $this->permission = $announcement->permission;
        $this->active = $announcement->active;

        $this->typeName = $announcement->type->name();
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
        if ($fields == "search") return;

        $type = AnnouncementType::from($this->type);
        $this->isGlobal = $type->isGlobal();
        if ($this->isGlobal) {
            $this->server = null;
        }
    }

    public function addAnnouncement()
    {
        $this->resetInput();
        $this->type = 1; // Set type to 1 by default.
        $this->active = true; // Set active to true by default.
        $this->isGlobal = true; // The default type is a global type.
    }

    public function createAnnouncement()
    {
        $validatedData = $this->validate();

        $sound = empty($validatedData['sound']) ? null : $validatedData['sound'];
        $server = empty($validatedData['server']) ? null : $validatedData['server'];
        $condition = empty($validatedData['condition']) ? null : $validatedData['condition'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        Announcement::create([
            'type' => $validatedData['type'],
            'message' => $validatedData['message'],
            'sound' => $sound,
            'server' => $server,
            'condition' => $condition,
            'expires' => $expires,

            'permission' => $validatedData['permission'],
            'active' => $validatedData['active'],
        ]);

        session()->flash('message', 'Successfully Created Announcement');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function editAnnouncement(Announcement $announcement)
    {
        $this->resetInput();

        $this->announcementId = $announcement->id;
        $this->type = $announcement->type->value;
        $this->message = $announcement->message;
        $this->sound = $announcement->sound;
        $this->server = $announcement->server;
        $this->condition = $announcement->condition;
        $this->expires = $announcement->expires != null ? $announcement->expires->toDateTimeLocalString() : $announcement->expires;

        $this->isGlobal = $announcement->type->isGlobal();
        $this->permission = $announcement->permission;
        $this->active = $announcement->active;
    }

    public function updateAnnouncement()
    {
        $validatedData = $this->validate();

        $sound = empty($validatedData['sound']) ? null : $validatedData['sound'];
        $server = empty($validatedData['server']) ? null : $validatedData['server'];
        $condition = empty($validatedData['condition']) ? null : $validatedData['condition'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        Announcement::where('id', $this->announcementId)->update([
            'type' => $validatedData['type'],
            'message' => $validatedData['message'],
            'sound' => $sound,
            'server' => $server,
            'condition' => $condition,
            'expires' => $expires,

            'permission' => $validatedData['permission'],
            'active' => $validatedData['active'],
        ]);

        session()->flash('message', 'Announcement Updated Successfully');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->type = -1;
        $this->message = '';
        $this->sound = null;
        $this->server = null;
        $this->condition = null;
        $this->expires = null;

        $this->isGlobal = false;
        $this->permission = false;
        $this->active = false;
    }

    public function deleteAnnouncement(Announcement $announcement)
    {
        $this->deleteId = $announcement->id;
    }

    public function delete()
    {
        Announcement::find($this->deleteId)->delete();
    }

    public function render(): View
    {
        $announcements = Announcement::where('id', 'like', '%' . $this->search . '%')
            ->orWhere('message', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'ASC')->paginate(10);
        return view('livewire.announcements.show-announcements')->with('announcements', $announcements);
    }
}
