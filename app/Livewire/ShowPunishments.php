<?php

namespace App\Livewire;

use App\Helpers\TimeUtils;
use App\Models\Player\Player;
use App\Models\Punishment;
use App\Models\PunishmentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPunishments extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $punishmentId;

    public int $typeId;

    public ?string $typeName;

    public ?string $playerName;

    public ?string $punisherUUID;

    public ?string $punisherName;

    public ?string $reason;

    public ?string $server = null;

    public ?string $time;

    public ?string $end;

    public ?string $ip;

    public ?string $timeFormatted;

    public ?string $endFormatted;

    public bool $silent;

    public bool $active;

    public ?string $unbanner;

    public ?string $unbannerName;

    public ?string $unbanReason;

    public bool $isGlobal = false;

    public bool $isTemporary = false;

    public string $search = '';

    public int $per_page = 10;

    public int $deleteId;

    /* ------------- MODAL FIELDS ------------- */
    public ?string $player;

    protected function rules()
    {
        return [
            'typeId' => 'required|integer',
            //'playerUUID' => 'required|uuid|exists:players,uuid',
            'punisherUUID' => 'required|uuid',
            'time' => 'required|date',
            'end' => $this->isTemporary ? 'required|date' : '',
            'reason' => 'required|string',
            'server' => $this->isGlobal ? '' : 'required|string',
            'silent' => 'required|boolean',
            'active' => 'required|boolean',
        ];
    }

    #[Computed]
    public function punishmentTypeCases(): array
    {
        return PunishmentType::cases();
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
        $this->ip = $punishment->ip;

        $this->unbanner = $punishment->unbanner;
        $this->unbannerName = $punishment->getUnbannerName();
        $this->unbanReason = $punishment->unbanreason;

        $this->isTemporary = $punishment->type->isTemporary();
        $this->silent = $punishment->silent;
        $this->active = $punishment->active;
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
        if ($fields == 'search' || $fields == 'per_page') {
            $this->resetPage();

            return;
        }

        $type = PunishmentType::from($this->typeId);
        $this->isGlobal = $type->isGlobal();
        $this->isTemporary = $type->isTemporary();
        if ($this->isGlobal) {
            $this->server = null;
        }
        if (! $this->isTemporary) {
            $this->end = -1;
        }
    }

    public function addPunishment()
    {
        $this->resetInput();
        $this->typeId = 1; // Set type to 1 by default.
        $this->active = true; // Set active to true by default.
        $this->isTemporary = false; // Set isTemporary to false by default.
        $this->time = Carbon::now()->format('Y-m-d\Th:i');
        $this->punisherUUID = Auth::check() ? Auth::user()->getUUID() : null;
    }

    public function createPunishment()
    {
        $isPlayerUUID = false;
        if (Str::isUuid($this->player)) {
            $isPlayerUUID = true;
            $validatedData = $this->validate($this->rules() + ['player' => 'required|uuid|exists:players,uuid']);
        } else {
            $validatedData = $this->validate($this->rules() + ['player' => 'required|exists:players,username']);
        }
        $player = $validatedData['player'];
        if (! $isPlayerUUID) {
            $uuid = Player::getUUID($player);
        } else {
            $uuid = $player;
        }
        if ($uuid == null) {
            session()->flash('error', "Could not find player $player!");
            $this->closeModal('addPunishmentModal');

            return;
        }

        $type = PunishmentType::from($validatedData['typeId']);
        $punisher = $validatedData['punisherUUID'];
        $time = Carbon::parse($validatedData['time'])->getPreciseTimestamp(3);
        $end = $type->isTemporary() ? Carbon::parse($validatedData['end'])->getPreciseTimestamp(3) : -1;
        $reason = $validatedData['reason'];
        $server = $validatedData['server'];
        $silent = $validatedData['silent'];
        $active = $validatedData['active'];

        $ip = Player::getIP($uuid);
        if ($ip == null) {
            session()->flash('error', "Could not find valid ip for use $uuid!");
            $this->closeModal('addPunishmentModal');

            return;
        }

        Punishment::create([
            'type' => $type,
            'uuid' => $uuid,
            'punisher' => $punisher,
            'time' => $time,
            'end' => $end,
            'reason' => $reason,
            'ip' => $ip,
            'server' => $server,
            'silent' => $silent,
            'active' => $active,
        ]);

        session()->flash('message', 'Punishment Created Successfully');
        $this->closeModal('addPunishmentModal');
    }

    public function editPunishment(Punishment $punishment)
    {
        $this->resetInput();

        $this->punishmentId = $punishment->id;
        $this->typeId = $punishment->type->value;
        $this->player = $punishment->uuid;
        $this->punisherUUID = $punishment->punisher;
        $this->reason = $punishment->reason;
        $this->ip = $punishment->ip;
        $this->server = $punishment->server;
        $this->time = Carbon::createFromTimestamp($punishment->time / 1000);
        $this->end = $punishment->end == -1 ? -1 : Carbon::createFromTimestamp($punishment->end / 1000);

        $this->isGlobal = $punishment->type->isGlobal();
        $this->isTemporary = $punishment->type->isTemporary();
        $this->silent = $punishment->silent;
        $this->active = $punishment->active;
    }

    public function updatePunishment()
    {
        $isPlayerUUID = false;
        if (Str::isUuid($this->player)) {
            $isPlayerUUID = true;
            $validatedData = $this->validate($this->rules() + ['player' => 'required|uuid|exists:players,uuid']);
        } else {
            $validatedData = $this->validate($this->rules() + ['player' => 'required|exists:players,username']);
        }
        $player = $validatedData['player'];
        if (! $isPlayerUUID) {
            $uuid = Player::getUUID($player);
        } else {
            $uuid = $player;
        }
        if ($uuid == null) {
            session()->flash('error', "Could not find player $player!");
            $this->closeModal('addPunishmentModal');

            return;
        }
        $hasUUIDChanged = $this->player != $uuid;

        $id = $this->punishmentId;
        $type = PunishmentType::from($validatedData['typeId']);
        $punisher = $validatedData['punisherUUID'];
        $time = Carbon::parse($validatedData['time'])->getPreciseTimestamp(3);
        $end = $type->isTemporary() ? Carbon::parse($validatedData['end'])->getPreciseTimestamp(3) : -1;
        $reason = $validatedData['reason'];
        $server = $validatedData['server'];
        $silent = $validatedData['silent'];
        $active = $validatedData['active'];
        $ip = $this->ip;

        if ($hasUUIDChanged) {
            $ip = Player::getIP($uuid);
            if ($ip == null) {
                session()->flash('error', "Could not find valid ip for use $uuid!");
                $this->closeModal('addPunishmentModal');

                return;
            }
        }

        Punishment::where('id', $id)->update([
            'type' => $type,
            'uuid' => $uuid,
            'punisher' => $punisher,
            'time' => $time,
            'end' => $end,
            'reason' => $reason,
            'ip' => $ip,
            'server' => $server,
            'silent' => $silent,
            'active' => $active,
        ]);

        session()->flash('message', 'Punishment Updated Successfully');
        $this->closeModal('editPunishmentModal');
    }

    public function unban(Punishment $punishment)
    {
        $this->resetInput();

        $this->punishmentId = $punishment->id;
    }

    public function handleUnban()
    {
        $validatedData = $this->validate([
            'unbanReason' => 'required|string',
        ]);
        $id = $this->punishmentId;

        $unbanner = auth()->user()->getUUID();
        if ($unbanner == null) {
            session()->flash('error', 'User has non existing uuid!');

            return;
        }
        $unbanReason = $validatedData['unbanReason'];

        Punishment::where('id', $id)->update([
            'unbanner' => $unbanner,
            'unbanreason' => $unbanReason,
            'active' => false,
        ]);

        session()->flash('message', 'Punishment Updated Successfully');
        $this->closeModal('unbanPunishmentModal');
    }

    public function closeModal(?string $modalId = null)
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    private function resetInput()
    {
        $this->typeId = -1;
        $this->player = '';
        $this->playerName = '';
        $this->punisherUUID = '';
        $this->punisherName = '';
        $this->reason = '';
        $this->server = null;
        $this->time = '';
        $this->end = '';

        $this->unbanner = null;
        $this->unbanReason = null;

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
        $punishments = Punishment::join('players', 'punishments.uuid', 'players.uuid')
            ->select('id', 'type', 'punishments.uuid', 'players.username', 'punisher', 'time', 'punishments.ip', 'punishments.server', 'reason', 'active')
            ->where('id', 'like', '%'.$this->search.'%')
            ->orWhere('players.username', 'like', '%'.$this->search.'%')
            ->orWhere('punishments.ip', 'like', '%'.$this->search.'%')
            ->orWhere('punishments.server', 'like', '%'.$this->search.'%')
            ->orWhere('reason', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'DESC')->paginate($this->per_page);

        return view('livewire.punishments.show-punishments')->with('punishments', $punishments);
    }
}
