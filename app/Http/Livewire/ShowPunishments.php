<?php

namespace App\Http\Livewire;

use App\Models\Punishment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPunishments extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $punishmentId;
    public ?string $type, $playerName, $punisherName, $reason, $server;
    public ?string $time, $end;
    public bool $silent, $active;
    public string $search = '';
    public int $deleteId;

    protected function rules()
    {
        return [
            'type' => 'required|integer',
            'reason' => 'required|string',
            'active' => ''
        ];
    }

    public function showPunishment(Punishment $punishment)
    {
        $this->punishmentId = $punishment->id;
        $this->type = $punishment->type->name();
        $this->playerName = $punishment->getPlayerName();
        $this->punisherName = $punishment->getPunisherName();
        $this->reason = $punishment->reason;
        $this->server = $punishment->server;
        $this->time = $punishment->time;
        $this->end = $punishment->end;

        $this->silent = $punishment->silent;
        $this->active = $punishment->active;
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function editAnnouncement(Punishment $punishment)
    {
        $this->resetInput();

    }

    public function updateAnnouncement()
    {
        $validatedData = $this->validate();

        /*$expires = empty($validatedData['expires']) ? $validatedData['expires'] : Carbon::parse($validatedData['expires']);

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
        ]);*/
        session()->flash('message', 'Punishment Updated Successfully');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->type = '';
        $this->playerName = '';
        $this->punisherName = '';
        $this->reason = '';
        $this->server = '';
        $this->time = '';
        $this->end = '';

        $this->silent = false;
        $this->active = false;
    }

    public function deletePunishment(Punishment $punishment)
    {
        $this->deleteId = $punishment->id;
    }

    public function delete()
    {
        Punishment::find($this->deleteId)->delete();
    }

    public function render(): View
    {
        $punishments = Punishment::where('id', 'like', '%' . $this->search . '%')->orderBy('id', 'DESC')->paginate(10);
        return view('livewire.punishments.show-punishments')->with('punishments', $punishments);
    }
}
