<?php

namespace App\Livewire\Announcements;

use App\Models\Announcement;
use App\Models\AnnouncementType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowAnnouncements extends Component
{
    use AuthorizesRequests;

    public int $announcementId;

    public int $type;

    public ?string $message;

    public ?string $sound;

    public ?string $server;

    public ?string $condition;
    public ?string $interval;

    public ?string $expires;

    public bool $permission;

    public bool $active;

    public bool $showServerInput = false;

    public ?string $typeName;

    public int $deleteId;

    protected function rules(): array
    {
        return [
            'type' => 'required|integer',
            'message' => 'required|string|max:512',
            'sound' => 'string|nullable',
            'server' => 'string|nullable',
            'condition' => 'string|nullable',
            'interval' => 'string|nullable',
            'expires' => 'date|nullable',
            'permission' => 'required|boolean',
            'active' => 'required|boolean',
        ];
    }

    #[On('info')]
    public function showAnnouncement($rowId): void
    {
        $announcement = Announcement::find($rowId);
        if ($announcement == null) {
            session()->flash('error', 'Announcement $'.$rowId.' not found');

            return;
        }

        $this->announcementId = $announcement->id;
        $this->type = $announcement->type->value;
        $this->message = $announcement->message;
        $this->sound = $announcement->sound;
        $this->server = $announcement->server;
        $this->condition = $announcement->condition;
        $this->interval = $announcement->interval;
        $this->expires = $announcement->expires;

        $this->permission = $announcement->permission;
        $this->active = $announcement->active;

        $this->typeName = $announcement->type->name();
    }

    public function updated($fields): void
    {
        $this->validateOnly($fields);

        $type = AnnouncementType::from($this->type);
        if ($type->isGlobal()) {
            $this->server = null;
            $this->showServerInput = false;
        } else {
            $this->showServerInput = true;
        }
    }

    public function addAnnouncement(): void
    {
        $this->resetInput();
        $this->type = 1; // Set type to 1 by default.
        $this->active = true; // Set active to true by default.
    }

    public function createAnnouncement(): void
    {
        $this->authorize('edit_announcements');
        $validatedData = $this->validate();

        $sound = empty($validatedData['sound']) ? null : $validatedData['sound'];
        $server = empty($validatedData['server']) ? null : $validatedData['server'];
        $condition = empty($validatedData['condition']) ? null : $validatedData['condition'];
        $interval = empty($validatedData['interval']) ? null : $validatedData['interval'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        Announcement::create([
            'type' => $validatedData['type'],
            'message' => $validatedData['message'],
            'sound' => $sound,
            'server' => $server,
            'condition' => $condition,
            'interval' => $interval,
            'expires' => $expires,

            'permission' => $validatedData['permission'],
            'active' => $validatedData['active'],
        ]);

        session()->flash('message', 'Successfully Created Announcement');
        $this->closeModal('addAnnouncementModal');
        $this->refreshTable();
    }

    #[On('edit')]
    public function editAnnouncement($rowId): void
    {
        $announcement = Announcement::find($rowId);
        if ($announcement == null) {
            session()->flash('error', 'Announcement $'.$rowId.' not found');

            return;
        }
        $this->resetInput();

        $this->announcementId = $announcement->id;
        $this->type = $announcement->type->value;
        $this->message = $announcement->message;
        $this->sound = $announcement->sound;
        $this->server = $announcement->server;
        $this->interval = $announcement->condition;
        $this->condition = $announcement->interval;
        $this->expires = $announcement->expires != null ? $announcement->expires->toDateTimeLocalString() : $announcement->expires;

        $this->showServerInput = ! $announcement->type->isGlobal();
        $this->permission = $announcement->permission;
        $this->active = $announcement->active;
    }

    public function updateAnnouncement(): void
    {
        $this->authorize('edit_announcements');
        $validatedData = $this->validate();

        $sound = empty($validatedData['sound']) ? null : $validatedData['sound'];
        $server = empty($validatedData['server']) ? null : $validatedData['server'];
        $condition = empty($validatedData['condition']) ? null : $validatedData['condition'];
        $interval = empty($validatedData['interval']) ? null : $validatedData['interval'];
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        Announcement::where('id', $this->announcementId)->update([
            'type' => $validatedData['type'],
            'message' => $validatedData['message'],
            'sound' => $sound,
            'server' => $server,
            'condition' => $condition,
            'interval' => $interval,
            'expires' => $expires,

            'permission' => $validatedData['permission'],
            'active' => $validatedData['active'],
        ]);

        session()->flash('message', 'Announcement Updated Successfully');
        $this->closeModal('editAnnouncementModal');
        $this->refreshTable();
    }

    public function closeModal(?string $modalId = null): void
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    private function resetInput(): void
    {
        $this->type = -1;
        $this->message = '';
        $this->sound = null;
        $this->server = null;
        $this->condition = null;
        $this->interval = null;
        $this->expires = null;

        $this->showServerInput = false;
        $this->permission = false;
        $this->active = false;
    }

    #[On('delete')]
    public function deleteAnnouncement($rowId): void
    {
        $announcement = Announcement::find($rowId);
        if ($announcement == null) {
            session()->flash('error', 'Announcement $'.$rowId.' not found');

            return;
        }
        $this->deleteId = $announcement->id;
    }

    public function delete(): void
    {
        $this->authorize('edit_announcements');
        Announcement::find($this->deleteId)?->delete();
        $this->resetInput();
        $this->refreshTable();
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-announcements-table');
    }

    public function render(): View
    {
        return view('livewire.announcements.show-announcements');
    }
}
