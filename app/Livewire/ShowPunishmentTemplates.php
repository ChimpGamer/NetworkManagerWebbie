<?php

namespace App\Livewire;

use App\Models\PunishmentTemplate;
use App\Models\PunishmentType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPunishmentTemplates extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

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

    public string $search = '';
    public int $per_page = 10;

    protected function rules()
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

    #[\Livewire\Attributes\On('info')]
    public function showPunishmentTemplate($rowId)
    {
        $punishmentTemplate = PunishmentTemplate::find($rowId);
        //dump($template);
        $this->templateId = $punishmentTemplate->id;
        $this->name = $punishmentTemplate->name;
        $this->type = $punishmentTemplate->type->name();
        $this->duration = $punishmentTemplate->duration;
        $this->reason = $punishmentTemplate->reason;
        $this->server = $punishmentTemplate->server;
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
        if ($fields == 'search') {
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
            $this->duration = -1;
        }
    }

    public function addTemplate()
    {
        $this->resetInput();
    }

    public function createTemplate()
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
    }

    #[\Livewire\Attributes\On('edit')]
    public function editTemplate($rowId)
    {
        $punishmentTemplate = PunishmentTemplate::find($rowId);
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

    public function updateTemplate()
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
    }

    #[\Livewire\Attributes\On('delete')]
    public function deletePunishmentTemplate($rowId)
    {
        $punishmentTemplate = PunishmentTemplate::find($rowId);
        $this->deleteId = $punishmentTemplate->id;
        $this->deleteName = $punishmentTemplate->name;
    }

    public function delete()
    {
        PunishmentTemplate::find($this->deleteId)->delete();
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
        $this->name = '';
        $this->type = '';
        $this->typeId = 1;
        $this->duration = -1;
        $this->reason = '';
        $this->server = null;
    }

    public function render(): View
    {
        $punishmentTemplates = PunishmentTemplate::where('id', 'like', '%'.$this->search.'%')->paginate($this->per_page);

        return view('livewire.punishment_templates.show-punishment-templates')->with('punishmentTemplates', $punishmentTemplates);
    }
}
