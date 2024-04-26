<?php

namespace App\Livewire;

use App\Models\MOTD;
use Carbon\Carbon;
use Livewire\Component;

class ShowMotd extends Component
{
    public int $motdId;

    public string $text;

    public string $description;

    public string $customversion;

    public string $faviconUrl;

    public ?string $expires;

    public bool $maintenance_mode;

    public bool $enabled;

    protected $rules = [
        'text' => 'required|string',
        'description' => 'string',
        'customversion' => 'string',
        'faviconUrl' => 'url:http,https|ends_with:png,jpg',
        'expires' => 'date|nullable',
        'maintenance_mode' => 'required|boolean',
        'enabled' => 'required|boolean',
    ];

    public function addMotd()
    {
        $this->resetInput();
    }

    public function createMotd()
    {
        $validatedData = $this->validate();
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        MOTD::create([
            'text' => $validatedData['text'],
            'description' => $validatedData['description'],
            'customversion' => $validatedData['customversion'],
            'faviconUrl' => $validatedData['faviconUrl'],
            'expires' => $expires,
            'maintenance_mode' => $validatedData['maintenance_mode'],
            'enabled' => $validatedData['enabled'],
        ]);
        session()->flash('message', 'Successfully Created Motd');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function editMotd(Motd $motd)
    {
        $this->motdId = $motd->id;
        $this->text = $motd->text;
        $this->description = $motd->description;
        $this->customversion = $motd->customversion;
        $this->faviconUrl = $motd->faviconUrl;
        $this->expires = $motd->expires;
        $this->maintenance_mode = $motd->maintenance_mode;
        $this->enabled = $motd->enabled;
    }

    public function updateMotd()
    {
        $validatedData = $this->validate();
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        MOTD::where('id', $this->motdId)->update([
            'text' => $validatedData['text'],
            'description' => $validatedData['description'],
            'customversion' => $validatedData['customversion'],
            'faviconUrl' => $validatedData['faviconUrl'],
            'expires' => $expires,
            'maintenance_mode' => $validatedData['maintenance_mode'],
            'enabled' => $validatedData['enabled'],
        ]);
        session()->flash('message', 'Motd Updated Successfully');
        $this->resetInput();
        $this->dispatch('close-modal');
    }

    public function deleteMotd(MOTD $motd)
    {
        $this->motdId = $motd->id;
    }

    public function delete()
    {
        MOTD::find($this->motdId)->delete();
        $this->resetInput();
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->motdId = -1;
        $this->text = '';
        $this->description = '';
        $this->customversion = '';
        $this->faviconUrl = '';
        $this->expires = null;
        $this->maintenance_mode = false;
        $this->enabled = true;
    }

    public function render()
    {
        $motds = MOTD::all();

        return view('livewire.motd.show-motd', ['motds' => $motds]);
    }
}
