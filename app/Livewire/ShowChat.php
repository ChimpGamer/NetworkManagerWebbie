<?php

namespace App\Livewire;

use App\Models\Chat\ChatMessage;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Livewire\WithPagination;

class ShowChat extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public int $type = 1;

    public string $search = '';

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function render(): View|Application|Factory
    {
        $chatMessages = ChatMessage::join('players', 'chat.uuid', 'players.uuid')
            ->select('chat.uuid', 'chat.type', 'chat.message', 'chat.server', 'chat.time', 'players.username')
            ->where('type', $this->type)
            ->where(function (Builder $query) {
                $query->where('players.username', 'like', '%'.$this->search.'%')
                    ->orWhere('server', 'like', '%'.$this->search.'%')
                    ->orWhere('message', 'like', '%'.$this->search.'%');
            })
            ->orderBy('time', 'DESC')->paginate(10);

        return view('livewire.chat.show-chat')->with('chatmessages', $chatMessages);
    }
}
