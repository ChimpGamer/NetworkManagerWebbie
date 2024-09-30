<?php

namespace App\Livewire;

use App\Models\CommandLog;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCommandLog extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public int $per_page = 10;

    public function updated($name, $value): void
    {
        if ($name == 'search') {
            $this->resetPage();
        }
    }

    public function render(): View|Application|Factory
    {
        $executedCommands = CommandLog::join('players', 'command_log.uuid', 'players.uuid')
            ->select('command_log.uuid', 'command_log.command', 'command_log.server', 'command_log.time', 'players.username')
            ->where(function (Builder $query) {
                $query->where('players.username', 'like', '%'.$this->search.'%')
                    ->orWhere('server', 'like', '%'.$this->search.'%')
                    ->orWhere('command', 'like', '%'.$this->search.'%');
            })
            ->orderBy('time', 'DESC')->paginate($this->per_page);

        return view('livewire.commandlog.show-command-log')->with('executedCommands', $executedCommands);
    }
}
