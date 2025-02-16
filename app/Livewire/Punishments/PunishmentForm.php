<?php

namespace App\Livewire\Punishments;

use App\Models\PunishmentType;
use Livewire\Form;

class PunishmentForm extends Form
{
    public int $typeId = 1;

    public ?string $punisherUUID;

    public ?string $reason;

    public ?string $server = null;

    public ?string $time;

    public ?string $end;

    public ?string $ip;

    public ?string $timeFormatted;

    public ?string $endFormatted;

    public bool $silent = false;

    public bool $active = true;

    public bool $isGlobal = false;

    public bool $isTemporary = false;

    protected function rules(): array
    {
        return [
            'typeId' => 'required|integer',
            'punisherUUID' => 'required|uuid',
            'time' => 'required|date',
            'end' => $this->isTemporary ? 'required|date' : '',
            'reason' => 'required|string',
            'server' => $this->isGlobal ? '' : 'required|string',
            'silent' => 'required|boolean',
            'active' => 'required|boolean',
        ];
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
}
