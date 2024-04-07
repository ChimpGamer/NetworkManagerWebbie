<?php

namespace App\Livewire;

use App\Models\PunishmentTemplate;
use App\Models\PunishmentType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
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

    public string $search = '';

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

    public function showPunishmentTemplate(PunishmentTemplate $template)
    {
        //dump($template);
        $this->templateId = $template->id;
        $this->name = $template->name;
        $this->type = $template->type->name();
        $this->duration = $template->duration;
        $this->reason = $template->reason;
        $this->server = $template->server;
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
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function editTemplate(PunishmentTemplate $template)
    {
        $this->resetInput();

        $this->templateId = $template->id;
        $this->name = $template->name;
        $this->typeId = $template->type->value;
        $this->duration = $template->duration;
        $this->server = $template->server;
        $this->reason = $template->reason;

        $this->isGlobal = $template->type->isGlobal();
        $this->isTemporary = $template->type->isTemporary();
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
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function deletePunishmentTemplate(PunishmentTemplate $punishmentTemplate)
    {
        $this->deleteId = $punishmentTemplate->id;
    }

    public function delete()
    {
        PunishmentTemplate::find($this->deleteId)->delete();
    }

    public function closeModal()
    {
        $this->resetInput();
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
        $punishmentTemplates = PunishmentTemplate::where('id', 'like', '%'.$this->search.'%')->paginate(10);

        return view('livewire.punishment_templates.show-punishment-templates')->with('punishmentTemplates', $punishmentTemplates);
    }
}
