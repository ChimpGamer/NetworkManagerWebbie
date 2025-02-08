<?php

namespace Addons\UltimateJQMessages\Livewire;

use Addons\UltimateJQMessages\App\Models\JoinQuitMessage;
use Addons\UltimateJQMessages\App\Models\JoinQuitMessageType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowJoinQuitMessages extends Component
{
    public int $jqMessageId;

    public ?string $name;

    public ?JoinQuitMessageType $type;

    public ?string $message;

    public ?string $permission;

    #[Computed]
    public function JoinQuitMessageTypeCases(): array
    {
        return JoinQuitMessageType::cases();
    }

    public function addJQMessage(): void
    {
        $this->resetInput();
    }

    public function createJQMessage(): void
    {
        // name has to be unique.
        $validatedData = $this->validate([
            'name' => 'required|string|unique:Addons\UltimateJQMessages\App\Models\JoinQuitMessage,name',
            'type' => [Rule::enum(JoinQuitMessageType::class)],
            'message' => 'required|string',
            'permission' => 'string|nullable',
        ]);

        $name = $validatedData['name'];
        $type = $validatedData['type'];
        $message = $validatedData['message'];
        $permission = $validatedData['permission'] ?? null;

        JoinQuitMessage::create([
            'name' => $name,
            'type' => $type,
            'message' => $message,
            'permission' => $permission,
        ]);
        session()->flash('message', 'Successfully Added Join Quit Message');
        $this->closeModal('addJQMessageModal');
        $this->refreshTable();
    }

    #[On('edit')]
    public function editJQMessage($rowId): void
    {
        $joinQuitMessage = JoinQuitMessage::find($rowId);
        if ($joinQuitMessage == null) {
            session()->flash('error', 'Join Quit Message #'.$rowId.' not found');

            return;
        }

        $this->jqMessageId = $joinQuitMessage->id;
        $this->name = $joinQuitMessage->name;
        $this->type = $joinQuitMessage->type;
        $this->message = $joinQuitMessage->message;
        $this->permission = $joinQuitMessage->permission;
    }

    public function updateJQMessage(): void
    {
        $validatedData = $this->validate([
            'name' => 'required|string|exists:Addons\UltimateJQMessages\App\Models\JoinQuitMessage,name',
            'type' => [Rule::enum(JoinQuitMessageType::class)],
            'message' => 'required|string',
            'permission' => 'string|nullable',
        ]);

        $name = $validatedData['name'];
        $type = $validatedData['type'];
        $message = $validatedData['message'] ?? null;
        $permission = $validatedData['permission'] ?? null;

        JoinQuitMessage::where('id', $this->jqMessageId)->update([
            'name' => $name,
            'type' => $type,
            'message' => $message,
            'permission' => $permission,
        ]);
        session()->flash('message', 'Join Quit Message Updated Successfully');
        $this->closeModal('editJQMessageModal');
        $this->refreshTable();
    }

    #[On('delete')]
    public function deleteJQMessage($rowId): void
    {
        $joinQuitMessage = JoinQuitMessage::find($rowId);
        if ($joinQuitMessage == null) {
            session()->flash('error', 'Join Quit Message #'.$rowId.' not found');

            return;
        }

        $this->jqMessageId = $joinQuitMessage->id;
        $this->name = $joinQuitMessage->name;
    }

    public function delete(): void
    {
        JoinQuitMessage::find($this->jqMessageId)->delete();
        $this->closeModal('deleteJQMessageModal');
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
        $this->jqMessageId = -1;
        $this->name = null;
        $this->type = null;
        $this->message = null;
        $this->permission = null;
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-join-quit-messages-table');
    }

    public function render(): View|Factory|Application
    {
        return view('ultimatejqmessages::livewire.show-join-quit-messages');
    }
}
