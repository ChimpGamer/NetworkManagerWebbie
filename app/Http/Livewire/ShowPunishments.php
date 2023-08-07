<?php

namespace App\Http\Livewire;

use App\Helpers\TimeUtils;
use App\Models\Punishment;
use App\Models\PunishmentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPunishments extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $punishmentId, $typeId;
    public ?string $typeName,
        $playerUUID,
        $playerName,
        $punisherUUID,
        $punisherName,
        $reason,
        $server = null,
        $time,
        $end,
        $timeFormatted,
        $endFormatted;
    public bool $silent, $active, $isGlobal, $isTemporary;
    public string $search = '';
    public int $deleteId;

    protected function rules()
    {
        return [
            'typeId' => 'required|integer',
            'playerUUID' => 'required|string',
            'punisherUUID' => 'required|string',
            'time' => 'required|integer',
            'end' => 'required|integer',
            'reason' => 'required|string',
            'server' => $this->isGlobal ? '' : 'required|string',
            'silent' => 'required|boolean',
            'active' => 'required|boolean'
        ];
    }

    public function showPunishment(Punishment $punishment)
    {
        $this->punishmentId = $punishment->id;
        $this->typeName = $punishment->type->name();
        $this->playerName = $punishment->getPlayerName();
        $this->punisherName = $punishment->getPunisherName();
        $this->reason = $punishment->reason;
        $this->server = $punishment->type->isGlobal() ? 'Global' : $punishment->server;
        $this->time = $punishment->time;
        $this->end = $punishment->end;
        $this->timeFormatted = TimeUtils::formatTimestamp($this->time);
        $this->endFormatted = TimeUtils::formatTimestamp($this->end);

        $this->isTemporary = $punishment->type->isTemporary();
        $this->silent = $punishment->silent;
        $this->active = $punishment->active;
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);

        $type = PunishmentType::from($this->typeId);
        $this->isGlobal = $type->isGlobal();
        $this->isTemporary = $type->isTemporary();
        if ($this->isGlobal) {
            $this->server = null;
        }
        if (!$this->isTemporary) {
            $this->end = -1;
        }
    }

    public function editPunishment(Punishment $punishment)
    {
        $this->resetInput();

        $this->punishmentId = $punishment->id;
        $this->typeId = $punishment->type->value;
        $this->playerUUID = $punishment->uuid;
        $this->punisherUUID = $punishment->punisher;
        $this->reason = $punishment->reason;
        $this->server = $punishment->server;
        $this->time = $punishment->time;
        $this->end = $punishment->end;

        $this->isGlobal = $punishment->type->isGlobal();
        $this->isTemporary = $punishment->type->isTemporary();
        $this->silent = $punishment->silent;
        $this->active = $punishment->active;
    }

    public function updatePunishment()
    {
        $validatedData = $this->validate();
        $id = $this->punishmentId;
        $type = PunishmentType::from($validatedData['typeId']);
        $uuid = $validatedData['playerUUID'];
        $punisher = $validatedData['punisherUUID'];
        $time = $validatedData['time'];
        $end = $type->isTemporary() ? $validatedData['end'] : -1;
        $reason = $validatedData['reason'];
        $server = $validatedData['server'];
        $silent = $validatedData['silent'];
        $active = $validatedData['active'];

        Punishment::where('id', $id)->update([
           'type' => $type,
           'uuid' => $uuid,
           'punisher' => $punisher,
           'time' => $time,
           'end' => $end,
           'reason' => $reason,
           'server' => $server,
           'silent' => $silent,
           'active' => $active
        ]);

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
        $this->typeId = -1;
        $this->playerUUID = '';
        $this->playerName = '';
        $this->punisherUUID = '';
        $this->punisherName = '';
        $this->reason = '';
        $this->server = null;
        $this->time = '';
        $this->end = '';

        $this->isGlobal = false;
        $this->isTemporary = true;
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
