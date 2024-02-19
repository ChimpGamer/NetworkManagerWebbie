<?php

namespace App\Livewire;

use App\Models\MOTD;
use Livewire\Component;

class ShowMotd extends Component
{
    public int $motdId;

    public string $text;

    public string $description;

    public string $customversion;

    public string $faviconUrl;

    public bool $enabled;

    protected $rules = [
        'text' => 'required|string',
        'description' => 'string',
        'customversion' => 'string',
        'faviconUrl' => 'url:http,https|ends_with:png,jpg',
        'enabled' => 'required|boolean',
    ];

    public function addMotd()
    {
        $this->resetInput();
    }

    public function createMotd()
    {
        $validatedData = $this->validate();

        MOTD::create([
            'text' => $validatedData['text'],
            'description' => $validatedData['description'],
            'customversion' => $validatedData['customversion'],
            'faviconUrl' => $validatedData['faviconUrl'],
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
        $this->enabled = $motd->enabled;
    }

    public function updateMotd()
    {
        $validatedData = $this->validate();

        MOTD::where('id', $this->motdId)->update([
            'text' => $validatedData['text'],
            'description' => $validatedData['description'],
            'customversion' => $validatedData['customversion'],
            'faviconUrl' => $validatedData['faviconUrl'],
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
        $this->enabled = true;
    }

    public function render()
    {
        $motds = MOTD::all();

        return view('livewire.motd.show-motd', ['motds' => $motds]);
    }
}
