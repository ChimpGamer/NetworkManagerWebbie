<?php

namespace App\Livewire;

use App\Models\CommandBlocker;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCommandBlocker extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    protected string $paginationTheme = 'bootstrap';

    public int $commandBlockerId;

    public ?string $command;

    public ?string $server;

    public ?string $customMessage;

    public bool $bypasspermission;

    public bool $enabled;

    public string $search = '';

    protected $rules = [
        'command' => 'required|string',
        'server' => 'string|nullable',
        'customMessage' => 'string|nullable',
        'bypasspermission' => 'boolean',
        'enabled' => 'required|boolean',
    ];

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function addCommandBlocker()
    {
        $this->resetInput();
    }

    public function createCommandBlocker()
    {
        $this->authorize('edit_commandblocker');
        $validatedData = $this->validate();

        $server = empty($validatedData['server']) ? null : $validatedData['server'];
        $customMessage = empty($validatedData['customMessage']) ? null : $validatedData['customMessage'];
        $bypasspermission = $validatedData['bypasspermission'];
        $enabled = $validatedData['enabled'];

        CommandBlocker::create([
            'command' => $validatedData['command'],
            'server' => $server,
            'customMessage' => $customMessage,
            'bypasspermission' => $bypasspermission,
            'enabled' => $enabled,
        ]);
        session()->flash('message', 'Successfully Added CommandBlocker');
        $this->closeModal('addCommandBlockerModal');
    }

    public function editCommandBlocker(CommandBlocker $commandBlocker)
    {
        $this->commandBlockerId = $commandBlocker->id;
        $this->command = $commandBlocker->command;
        $this->server = $commandBlocker->server;
        $this->customMessage = $commandBlocker->customMessage;
        $this->bypasspermission = $commandBlocker->bypasspermission;
        $this->enabled = $commandBlocker->enabled;
    }

    public function updateCommandBlocker()
    {
        $this->authorize('edit_commandblocker');
        $validatedData = $this->validate();

        $server = empty($validatedData['server']) ? null : $validatedData['server'];
        $customMessage = empty($validatedData['customMessage']) ? null : $validatedData['customMessage'];
        $bypasspermission = $validatedData['bypasspermission'];
        $enabled = $validatedData['enabled'];

        CommandBlocker::where('id', $this->commandBlockerId)->update([
            'command' => $validatedData['command'],
            'server' => $server,
            'customMessage' => $customMessage,
            'bypasspermission' => $bypasspermission,
            'enabled' => $enabled,
        ]);
        session()->flash('message', 'CommandBlocker Updated Successfully');
        $this->closeModal('editCommandBlockerModal');
    }

    public function deleteCommandBlocker(CommandBlocker $commandBlocker)
    {
        $this->commandBlockerId = $commandBlocker->id;
    }

    public function delete()
    {
        $this->authorize('edit_commandblocker');
        CommandBlocker::find($this->commandBlockerId)->delete();
        session()->flash('message', 'CommandBlocker Deleted Successfully');
        $this->closeModal('deleteCommandBlockerModal');
    }

    public function closeModal(?string $modalId = null)
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    public function resetInput()
    {
        $this->commandBlockerId = -1;
        $this->command = null;
        $this->server = null;
        $this->customMessage = null;
        $this->bypasspermission = false;
        $this->enabled = false;
    }

    public function render()
    {
        $blockedCommands = CommandBlocker::where('command', 'like', '%'.$this->search.'%')->orderBy('id', 'DESC')->paginate(10);

        return view('livewire.commandblocker.show-commandblocker')->with('blockedcommands', $blockedCommands);
    }
}
