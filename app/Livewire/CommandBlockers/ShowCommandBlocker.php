<?php

namespace App\Livewire\CommandBlockers;

use App\Models\CommandBlocker;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowCommandBlocker extends Component
{
    use AuthorizesRequests;

    public int $commandBlockerId;

    public ?string $name;

    public ?string $description;

    public ?string $command;

    public ?string $server;

    public ?string $customMessage;

    public bool $bypasspermission;

    public bool $enabled;

    protected array $rules = [
        'name' => 'required|string|alpha_dash|max:128',
        'description' => 'string|nullable',
        'command' => 'required|string',
        'server' => 'string|nullable',
        'customMessage' => 'string|nullable',
        'bypasspermission' => 'boolean',
        'enabled' => 'required|boolean',
    ];

    #[On('info')]
    public function showCommandBlock($rowId): void
    {
        $commandBlocker = CommandBlocker::find($rowId);
        if ($commandBlocker == null) {
            session()->flash('error', 'Command Blocker #'.$rowId.' not found');

            return;
        }

        $this->commandBlockerId = $commandBlocker->id;
        $this->name = $commandBlocker->name;
        $this->description = $commandBlocker->description;
        $this->command = $commandBlocker->command;
        $this->server = $commandBlocker->server;
        $this->customMessage = $commandBlocker->customMessage;
        $this->bypasspermission = $commandBlocker->bypasspermission;
        $this->enabled = $commandBlocker->enabled;
    }

    public function addCommandBlocker(): void
    {
        $this->resetInput();
    }

    public function createCommandBlocker(): void
    {
        $this->authorize('edit_commandblocker');
        $validatedData = $this->validate();

        $description = empty($validatedData['description']) ? null : $validatedData['description'];
        $server = empty($validatedData['server']) ? null : $validatedData['server'];
        $customMessage = empty($validatedData['customMessage']) ? null : $validatedData['customMessage'];
        $bypasspermission = $validatedData['bypasspermission'];
        $enabled = $validatedData['enabled'];

        CommandBlocker::create([
            'name' => $validatedData['name'],
            'description' => $description,
            'command' => $validatedData['command'],
            'server' => $server,
            'customMessage' => $customMessage,
            'bypasspermission' => $bypasspermission,
            'enabled' => $enabled,
        ]);
        session()->flash('message', 'Successfully Added CommandBlocker');
        $this->closeModal('addCommandBlockerModal');
        $this->refreshTable();
    }

    #[On('edit')]
    public function editCommandBlocker($rowId): void
    {
        $commandBlocker = CommandBlocker::find($rowId);
        if ($commandBlocker == null) {
            session()->flash('error', 'Command Blocker #'.$rowId.' not found');

            return;
        }

        $this->commandBlockerId = $commandBlocker->id;
        $this->name = $commandBlocker->name;
        $this->description = $commandBlocker->description;
        $this->command = $commandBlocker->command;
        $this->server = $commandBlocker->server;
        $this->customMessage = $commandBlocker->customMessage;
        $this->bypasspermission = $commandBlocker->bypasspermission;
        $this->enabled = $commandBlocker->enabled;
    }

    public function updateCommandBlocker(): void
    {
        $this->authorize('edit_commandblocker');
        $validatedData = $this->validate();

        $description = empty($validatedData['description']) ? null : $validatedData['description'];
        $server = empty($validatedData['server']) ? null : $validatedData['server'];
        $customMessage = empty($validatedData['customMessage']) ? null : $validatedData['customMessage'];
        $bypasspermission = $validatedData['bypasspermission'];
        $enabled = $validatedData['enabled'];

        CommandBlocker::where('id', $this->commandBlockerId)->update([
            'name' => $validatedData['name'],
            'description' => $description,
            'command' => $validatedData['command'],
            'server' => $server,
            'customMessage' => $customMessage,
            'bypasspermission' => $bypasspermission,
            'enabled' => $enabled,
        ]);
        session()->flash('message', 'CommandBlocker Updated Successfully');
        $this->closeModal('editCommandBlockerModal');
        $this->refreshTable();
    }

    #[On('delete')]
    public function deleteCommandBlocker($rowId): void
    {
        $commandBlocker = CommandBlocker::find($rowId);
        if ($commandBlocker == null) {
            session()->flash('error', 'Command Blocker #'.$rowId.' not found');

            return;
        }
        $this->commandBlockerId = $commandBlocker->id;
    }

    public function delete(): void
    {
        $this->authorize('edit_commandblocker');
        CommandBlocker::find($this->commandBlockerId)->delete();
        session()->flash('message', 'CommandBlocker Deleted Successfully');
        $this->closeModal('deleteCommandBlockerModal');
        $this->refreshTable();
    }

    public function closeModal(?string $modalId = null): void
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    public function resetInput(): void
    {
        $this->commandBlockerId = -1;
        $this->name = null;
        $this->description = null;
        $this->command = null;
        $this->server = null;
        $this->customMessage = null;
        $this->bypasspermission = false;
        $this->enabled = false;
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-command-blockers-table');
    }

    public function render()
    {
        return view('livewire.commandblocker.show-commandblocker');
    }
}
