<?php

namespace App\Livewire;

use App\Models\Chat\ChatLog;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Livewire\WithPagination;

class ShowChatLogs extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public int $per_page = 10;

    public ?string $deleteId;

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function deleteChatlog(ChatLog $chatLog): void
    {
        $this->deleteId = $chatLog->uuid;
    }

    public function delete()
    {
        ChatLog::find($this->deleteId)->delete();
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
        $this->deleteId = null;
    }

    public function render(): View|Application|Factory
    {
        $chatLogs = ChatLog::join('players as creator_player', 'chatlogs.creator', 'creator_player.uuid')
            ->join('players as tracked_player', 'chatlogs.tracked', 'tracked_player.uuid')
            ->select('chatlogs.uuid', 'chatlogs.creator', 'chatlogs.tracked', 'chatlogs.server', 'chatlogs.time', 'creator_player.username as creator_player_username', 'tracked_player.username as tracked_player_username')
            ->where(function (Builder $query) {
                $query->where('creator_player.username', 'like', '%'.$this->search.'%')
                    ->where('tracked_player.username', 'like', '%'.$this->search.'%')
                    ->orWhere('server', 'like', '%'.$this->search.'%');
            })
            ->orderBy('time', 'DESC')->paginate($this->per_page);

        return view('livewire.chatlogs.show-chatlogs')->with('chatlogs', $chatLogs);
    }
}
