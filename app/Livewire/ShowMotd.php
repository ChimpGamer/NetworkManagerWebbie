<?php

namespace App\Livewire;

use App\Models\MOTD;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    protected array $rules = [
        'text' => 'required|string|max:384',
        'description' => 'string',
        'customversion' => 'string|max:128',
        'faviconUrl' => 'url:http,https|ends_with:png,jpg',
        'expires' => 'date|nullable',
        'maintenance_mode' => 'required|boolean',
        'enabled' => 'required|boolean',
    ];

    public function addMotd(): void
    {
        $this->resetInput();
    }

    public function createMotd(): void
    {
        $validatedData = $this->validate();
        $expires = empty($validatedData['expires']) ? null : $validatedData['expires'];

        $motd = MOTD::create([
            'text' => $validatedData['text'],
            'description' => $validatedData['description'],
            'customversion' => $validatedData['customversion'],
            'faviconUrl' => $validatedData['faviconUrl'],
            'expires' => $expires,
            'maintenance_mode' => $validatedData['maintenance_mode'],
            'enabled' => $validatedData['enabled'],
        ]);
        session()->flash('message', 'Successfully Created Motd '.$motd->id);
        Log::driver('auditlog')->info(Auth::user()->username.' created a new MOTD');
        $this->closeModal('addMotdModal');
    }

    public function editMotd(Motd $motd): void
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

    public function updateMotd(): void
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
        Log::driver('auditlog')->info(Auth::user()->username.' updated MOTD '.$this->motdId);
        $this->closeModal('editMotdModal');
    }

    public function deleteMotd(MOTD $motd): void
    {
        $this->motdId = $motd->id;
    }

    public function delete(): void
    {
        MOTD::find($this->motdId)->delete();
        Log::driver('auditlog')->info(Auth::user()->username.' deleted MOTD '.$this->motdId);
        $this->resetInput();
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
        $this->motdId = -1;
        $this->text = '';
        $this->description = '';
        $this->customversion = '';
        $this->faviconUrl = '';
        $this->expires = null;
        $this->maintenance_mode = false;
        $this->enabled = true;
    }

    public function render(): View|Application
    {
        $motds = MOTD::all();

        return view('livewire.motd.show-motd', ['motds' => $motds]);
    }
}
