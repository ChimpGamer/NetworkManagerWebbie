<?php

namespace App\Http\Livewire;

use App\Models\PunishmentTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPunishmentTemplates extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $templateId;
    public ?string $type, $name, $reason, $server;
    public ?string $duration;
    public string $search = '';

    protected function rules()
    {
        return [
            'type' => 'required|integer',
            'duration' => 'required|integer',
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
    }

    public function editTemplate(PunishmentTemplate $template)
    {
        $this->resetInput();

    }

    public function updateTemplate()
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
        session()->flash('message', 'Punishment Template Updated Successfully');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->name = '';
        $this->type = '';
        $this->duration = '';
        $this->reason = '';
        $this->server = '';
    }

    public function render(): View
    {
        $punishmentTemplates = PunishmentTemplate::where('id', 'like', '%' . $this->search . '%')->paginate(10);
        return view('livewire.punishment_templates.show-punishment-templates')->with('punishmentTemplates', $punishmentTemplates);
    }
}
