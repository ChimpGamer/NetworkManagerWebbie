<?php

namespace App\Livewire;

use App\Models\Chat\ChatLog;
use App\Models\Chat\ChatMessage;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Livewire\WithPagination;

class ShowChatLog extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public int $per_page = 10;

    public ChatLog $chatLog;

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function render(): View|Application|Factory
    {
        $timeMinus12H = Carbon::createFromTimestampMs($this->chatLog->time)->subHours(12)->getTimestampMs();
        $chatMessages = ChatMessage::join('players', 'chat.uuid', 'players.uuid')
            ->select('chat.uuid', 'chat.type', 'chat.message', 'chat.server', 'chat.time', 'players.username')
            ->where('chat.uuid', $this->chatLog->tracked)
            ->whereBetween('chat.time', [$timeMinus12H, $this->chatLog->time])
            ->where('message', 'not like', '/%')
            ->where(function (Builder $query) {
                $query->where('message', 'like', '%'.$this->search.'%')
                    ->orWhere('server', 'like', '%'.$this->search.'%');
            })
            ->orderBy('id', 'DESC')
            ->limit(100)
            ->paginate($this->per_page);

        return view('livewire.chatlogs.show-chatlog')->with('chatMessages', $chatMessages);
    }
}
