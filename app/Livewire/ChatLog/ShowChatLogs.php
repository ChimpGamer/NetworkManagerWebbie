<?php

namespace App\Livewire\ChatLog;

use App\Models\Chat\ChatLog;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowChatLogs extends Component
{
    public ?string $deleteId;

    #[On('delete')]
    public function deleteChatlog($rowId): void
    {
        $chatLog = ChatLog::find($rowId);
        if ($chatLog == null) {
            session()->flash('error', 'ChatLog '.$rowId.' not found');

            return;
        }

        $this->deleteId = $chatLog->uuid;
    }

    public function delete(): void
    {
        ChatLog::find($this->deleteId)->delete();
        $this->resetInput();
        $this->refreshTable();
    }

    private function resetInput(): void
    {
        $this->deleteId = null;
    }

    public function closeModal(?string $modalId = null): void
    {
        $this->resetInput();
        if ($modalId != null) {
            $this->dispatch('close-modal', $modalId);
        }
    }

    private function refreshTable(): void
    {
        $this->dispatch('pg:eventRefresh-chat-logs-table');
    }

    public function render(): View|Application|Factory
    {
        return view('livewire.chatlogs.show-chatlogs');
    }
}
