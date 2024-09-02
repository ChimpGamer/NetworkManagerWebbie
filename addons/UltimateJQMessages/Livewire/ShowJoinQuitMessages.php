<?php

namespace Addons\UltimateJQMessages\Livewire;

use Addons\UltimateJQMessages\App\Models\JoinQuitMessage;
use Addons\UltimateJQMessages\App\Models\JoinQuitMessageType;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ShowJoinQuitMessages extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $jqMessageId;

    public ?string $name;

    public ?JoinQuitMessageType $type;

    public ?string $message;

    public ?string $permission;

    public string $search = '';
    public int $per_page = 10;

    #[Computed]
    public function JoinQuitMessageTypeCases(): array
    {
        return JoinQuitMessageType::cases();
    }

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function addJQMessage()
    {
        $this->resetInput();
    }

    public function createJQMessage()
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
    }

    public function editJQMessage(JoinQuitMessage $joinQuitMessage)
    {
        $this->jqMessageId = $joinQuitMessage->id;
        $this->name = $joinQuitMessage->name;
        $this->type = $joinQuitMessage->type;
        $this->message = $joinQuitMessage->message;
        $this->permission = $joinQuitMessage->permission;
    }

    public function updateJQMessage()
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
    }

    public function deleteJQMessage(JoinQuitMessage $joinQuitMessage)
    {
        $this->jqMessageId = $joinQuitMessage->id;
        $this->name = $joinQuitMessage->name;
    }

    public function delete()
    {
        JoinQuitMessage::find($this->jqMessageId)->delete();
        $this->closeModal('deleteJQMessageModal');
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
        $this->jqMessageId = -1;
        $this->name = null;
        $this->type = null;
        $this->message = null;
        $this->permission = null;
    }

    public function render()
    {
        $messages = JoinQuitMessage::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('type', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'DESC')->paginate($this->per_page);

        return view('ultimatejqmessages::livewire.show-join-quit-messages')->with('messages', $messages);
    }
}
