<?php

namespace App\Livewire\Punishments;

use App\Helpers\TimeUtils;
use App\Models\Player\Player;
use App\Models\Punishment;
use App\Models\PunishmentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowPunishments extends Component
{
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

    public int $deleteId;

    /* ------------- MODAL FIELDS ------------- */
    public ?string $player;

    protected function rules(): array
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

    #[On('info')]
    public function showPunishment($rowId): void
    {
        $punishment = Punishment::find($rowId);
        if ($punishment == null) {
            session()->flash('error', 'Punishment #'.$rowId.' not found');

            return;
        }

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

    public function updated($fields): void
    {
        $this->validateOnly($fields);

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

    public function addPunishment(): void
    {
        $this->resetInput();
        $this->typeId = 1; // Set type to 1 by default.
        $this->active = true; // Set active to true by default.
        $this->isTemporary = false; // Set isTemporary to false by default.
        $this->time = Carbon::now()->format('Y-m-d\Th:i');
        $this->punisherUUID = Auth::check() ? Auth::user()->getUUID() : null;
    }

    public function createPunishment(): void
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
        $this->refreshTable();
    }

    #[On('edit')]
    public function editPunishment($rowId): void
    {
        $punishment = Punishment::find($rowId);
        if ($punishment == null) {
            session()->flash('error', 'Punishment #'.$rowId.' not found');

            return;
        }
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

    public function updatePunishment(): void
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
        $this->refreshTable();
    }

    #[On('unban')]
    public function unban($rowId): void
    {
        $punishment = Punishment::find($rowId);
        if ($punishment == null) {
            session()->flash('error', 'Punishment #'.$rowId.' not found');

            return;
        }
        $this->resetInput();

        $this->punishmentId = $punishment->id;
    }

    public function handleUnban(): void
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

    #[On('delete')]
    public function deletePunishment($rowId): void
    {
        $punishment = Punishment::find($rowId);
        if ($punishment == null) {
            session()->flash('error', 'Punishment #'.$rowId.' not found');

            return;
        }

        // TODO: Prevent deleting punishment when punishment is still active.
        /*if ($punishment->active) {
            session()->flash('error', 'Punishment #'.$rowId.' is still active! Undo the punishment before you delete it!');
            usleep(99559);
            $this->closeModal('deletePunishmentModal');

            return;
        }*/
        $this->deleteId = $punishment->id;
    }

    public function delete(): void
    {
        Punishment::find($this->deleteId)?->delete();
        $this->refreshTable();
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-punishments-table');
    }

    public function render(): View
    {
        return view('livewire.punishments.show-punishments');
    }
}
