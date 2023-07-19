<?php

namespace App\Http\Livewire;

use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
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
    public bool $permission, $active;
    public string $search = '';
    public int $deleteId;

    protected function rules()
    {
        return [
            'type' => 'required|integer',
            'message' => 'required|string|max:512',
            'sound' => '',
            'server' => '',
            'condition' => '',
            'expires' => '',
            'permission' => '',
            'active' => ''
        ];
    }

    public function showAnnouncement(Announcement $announcement)
    {
        $this->announcementId = $announcement->id;
        $this->type = $announcement->type;
        $this->message = $announcement->message;
        $this->sound = $announcement->sound;
        $this->server = $announcement->server;
        $this->condition = $announcement->condition;
        $this->expires = $announcement->expires != null ? $announcement->expires->toDateTimeLocalString() : $announcement->expires;

        $this->permission = $announcement->permission;
        $this->active = $announcement->active;
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function editAnnouncement(Announcement $announcement)
    {
        $this->resetInput();

        $this->announcementId = $announcement->id;
        $this->type = $announcement->type;
        $this->message = $announcement->message;
        $this->sound = $announcement->sound;
        $this->server = $announcement->server;
        $this->condition = $announcement->condition;
        $this->expires = $announcement->expires != null ? $announcement->expires->toDateTimeLocalString() : $announcement->expires;

        $this->permission = $announcement->permission;
        $this->active = $announcement->active;
    }

    public function updateAnnouncement()
    {
        $validatedData = $this->validate();

        $expires = empty($validatedData['expires']) ? $validatedData['expires'] : Carbon::parse($validatedData['expires']);

        Log::info('validateData=' . implode(',', $validatedData));
        Log::info('expires=' . $expires);

        Announcement::where('id', $this->announcementId)->update([
            'type' => $validatedData['type'],
            'message' => $validatedData['message'],
            'sound' => $validatedData['sound'],
            'server' => $validatedData['server'],
            'condition' => $validatedData['condition'],
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
        $this->sound = '';
        $this->server = '';
        $this->condition = '';
        $this->expires = '';

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
        $announcements = Announcement::where('id', 'like', '%' . $this->search . '%')->orderBy('id', 'ASC')->paginate(10);
        return view('livewire.announcements.show-announcements')->with('announcements', $announcements);
    }
}
