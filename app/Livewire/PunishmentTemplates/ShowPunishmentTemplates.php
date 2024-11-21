<?php

namespace App\Livewire\PunishmentTemplates;

use App\Models\PunishmentTemplate;
use App\Models\PunishmentType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowPunishmentTemplates extends Component
{
    use AuthorizesRequests;

    public int $templateId;

    public int $typeId;

    public ?string $type;

    public ?string $name;

    public ?string $reason;

    public ?string $server;

    public ?int $duration;

    public bool $isGlobal;

    public bool $isTemporary;

    public int $deleteId;

    public ?string $deleteName;

    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'typeId' => 'required|integer',
            'duration' => 'integer',
            'server' => 'string|nullable',
            'reason' => 'required|string',
        ];
    }

    #[Computed]
    public function punishmentTypeCases(): array
    {
        return PunishmentType::cases();
    }

    #[On('info')]
    public function showPunishmentTemplate($rowId): void
    {
        $punishmentTemplate = PunishmentTemplate::find($rowId);
        if ($punishmentTemplate == null) {
            session()->flash('error', 'Punishment template not found');
            return;
        }
        //dump($template);
        $this->templateId = $punishmentTemplate->id;
        $this->name = $punishmentTemplate->name;
        $this->type = $punishmentTemplate->type->name();
        $this->duration = $punishmentTemplate->duration;
        $this->reason = $punishmentTemplate->reason;
        $this->server = $punishmentTemplate->server;
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
            $this->duration = -1;
        }
    }

    public function addTemplate(): void
    {
        $this->resetInput();
    }

    public function createTemplate(): void
    {
        $this->authorize('edit_pre_punishments');
        $validatedData = $this->validate();

        $type = PunishmentType::from($validatedData['typeId']);

        PunishmentTemplate::create([
            'name' => $validatedData['name'],
            'type' => $type,
            'duration' => $validatedData['duration'],
            'server' => $validatedData['server'],
            'reason' => $validatedData['reason'],
        ]);

        session()->flash('message', 'Successfully Created Template');
        $this->closeModal('addTemplateModal');
        $this->refreshTable();
    }

    #[On('edit')]
    public function editTemplate($rowId): void
    {
        $punishmentTemplate = PunishmentTemplate::find($rowId);
        if ($punishmentTemplate == null) {
            session()->flash('error', 'Punishment template not found');
            return;
        }
        $this->resetInput();

        $this->templateId = $punishmentTemplate->id;
        $this->name = $punishmentTemplate->name;
        $this->typeId = $punishmentTemplate->type->value;
        $this->duration = $punishmentTemplate->duration;
        $this->server = $punishmentTemplate->server;
        $this->reason = $punishmentTemplate->reason;

        $this->isGlobal = $punishmentTemplate->type->isGlobal();
        $this->isTemporary = $punishmentTemplate->type->isTemporary();
    }

    public function updateTemplate(): void
    {
        $this->authorize('edit_pre_punishments');
        $validatedData = $this->validate();
        //dump($validatedData);

        $type = PunishmentType::from($validatedData['typeId']);

        PunishmentTemplate::where('id', $this->templateId)->update([
            'name' => $validatedData['name'],
            'type' => $type,
            'duration' => $validatedData['duration'],
            'server' => $validatedData['server'],
            'reason' => $validatedData['reason'],
        ]);

        session()->flash('message', 'Successfully Updated Template');
        $this->closeModal('editTemplateModal');
        $this->refreshTable();
    }

    #[On('delete')]
    public function deletePunishmentTemplate($rowId): void
    {
        $punishmentTemplate = PunishmentTemplate::find($rowId);
        if ($punishmentTemplate == null) {
            session()->flash('error', 'Punishment template not found');
            return;
        }
        $this->deleteId = $punishmentTemplate->id;
        $this->deleteName = $punishmentTemplate->name;
    }

    public function delete(): void
    {
        PunishmentTemplate::find($this->deleteId)?->delete();
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
        $this->name = '';
        $this->type = '';
        $this->typeId = 1;
        $this->duration = -1;
        $this->reason = '';
        $this->server = null;
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-punishment-templates-table');
    }

    public function render(): View
    {
        return view('livewire.punishment_templates.show-punishment-templates');
    }
}
