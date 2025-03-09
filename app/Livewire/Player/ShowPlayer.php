<?php

namespace App\Livewire\Player;

use App\Livewire\Punishments\PunishmentForm;
use App\Models\Permissions\GroupMember;
use App\Models\Permissions\PermissionPlayer;
use App\Models\Permissions\PlayerPermission;
use App\Models\Player\Player;
use App\Models\Punishment;
use App\Models\PunishmentType;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShowPlayer extends Component
{
    public Player $player;

    public PunishmentForm $punishment;

    public bool $isGlobal = false;

    public bool $isTemporary = false;

    public function updated($fields): void
    {
        $this->punishment->updated($fields);
    }
    #[Computed]
    public function punishmentTypeCases(): array
    {
        return PunishmentType::cases();
    }

    public function punishPlayer(): void
    {
        $this->punishment->reset();
        $this->punishment->typeId = 1; // Set type to 1 by default.
        $this->punishment->active = true; // Set active to true by default.
        $this->punishment->isTemporary = false; // Set isTemporary to false by default.
        $this->punishment->time = Carbon::now()->format('Y-m-d\Th:i');
        $this->punishment->punisherUUID = Auth::check() ? Auth::user()->getUUID() : null;
    }

    public function punish(): void
    {
        $validatedData = $this->validate();

        $type = PunishmentType::from($validatedData['typeId']);
        $punisher = $validatedData['punisherUUID'];
        $time = Carbon::parse($validatedData['time'])->getPreciseTimestamp(3);
        $end = $type->isTemporary() ? Carbon::parse($validatedData['end'])->getPreciseTimestamp(3) : -1;
        $reason = $validatedData['reason'];
        $server = $validatedData['server'];
        $silent = $validatedData['silent'];
        $active = $validatedData['active'];

        $ip = $this->player->ip;
        if ($ip == null) {
            session()->flash('error', 'Could not find valid ip for use '.$this->player->uuid.'!');
            $this->closeModal('addPunishmentModal');

            return;
        }

        Punishment::create([
            'type' => $type,
            'uuid' => $this->player->uuid,
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
        $this->refreshPlayerPunishmentsTable();
    }

    public function deletePlayer()
    {
        $this->player->delete();
        GroupMember::where('playeruuid', $this->player->uuid)->delete();
        PlayerPermission::where('playeruuid', $this->player->uuid)->delete();
        PermissionPlayer::where('uuid', $this->player->uuid)->delete();
        Punishment::where('uuid', $this->player->uuid)->delete();

        return \redirect()->route('players.index');
    }

    public function closeModal(?string $modalId = null): void
    {
        $this->punishment->reset();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    private function refreshPlayerPunishmentsTable(): void
    {
        $this->dispatch('pg:eventRefresh-player-punishments-table');
    }

    public function render(): View
    {
        return view('livewire.players.show-player')->with('player', $this->player);
    }
}
