<?php

namespace App\Http\Livewire;

use App\Models\CommandBlocker;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCommandBlocker extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $commandBlockerId;
    public ?string $command, $server, $customMessage;
    public bool $bypasspermission;

    public string $search = '';

    protected $rules = [
        'command' => 'required|string',
        'server' => 'string|nullable',
        'customMessage' => 'string|nullable',
        'bypasspermission' => 'boolean',
    ];

    public function addCommandBlocker() {
        $this->resetInput();
    }

    public function createCommandBlocker() {
        $validatedData = $this->validate();

        $server = empty($validatedData['server']) ? null : $validatedData['server'];
        $customMessage = empty($validatedData['customMessage']) ? null : $validatedData['customMessage'];
        $bypasspermission = $validatedData['bypasspermission'];

        CommandBlocker::create([
            'command' => $validatedData['command'],
            'server' => $server,
            'customMessage' => $customMessage,
            'bypasspermission' => $bypasspermission,
        ]);
        session()->flash('message', 'Successfully Added CommandBlocker');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function editCommandBlocker(CommandBlocker $commandBlocker)
    {
        $this->commandBlockerId = $commandBlocker->id;
        $this->command = $commandBlocker->command;
        $this->server = $commandBlocker->server;
        $this->customMessage = $commandBlocker->customMessage;
        $this->bypasspermission = $commandBlocker->bypasspermission;
    }

    public function updateCommandBlocker()
    {
        $validatedData = $this->validate();

        $server = empty($validatedData['server']) ? null : $validatedData['server'];
        $customMessage = empty($validatedData['customMessage']) ? null : $validatedData['customMessage'];
        $bypasspermission = $validatedData['bypasspermission'];

        CommandBlocker::where('id', $this->commandBlockerId)->update([
            'command' => $validatedData['command'],
            'server' => $server,
            'customMessage' => $customMessage,
            'bypasspermission' => $bypasspermission,
        ]);
        session()->flash('message', 'CommandBlocker Updated Successfully');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteCommandBlocker(CommandBlocker $commandBlocker)
    {
        $this->commandBlockerId = $commandBlocker->id;
    }

    public function delete()
    {
        CommandBlocker::find($this->commandBlockerId)->delete();
        session()->flash('message', 'CommandBlocker Deleted Successfully');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function closeModal()
    {
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->commandBlockerId = -1;
        $this->command = null;
        $this->server = null;
        $this->customMessage = null;
        $this->bypasspermission = false;
    }

    public function render()
    {
        $blockedCommands = CommandBlocker::where('command', 'like', '%'.$this->search.'%')->orderBy('id', 'DESC')->paginate(10);
        return view('livewire.commandblocker.show-commandblocker')->with('blockedcommands', $blockedCommands);
    }
}
