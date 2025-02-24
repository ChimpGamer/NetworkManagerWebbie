<?php

namespace App\Livewire\Player;

use App\Models\Player\Player;
use App\Models\Player\Session;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PlayerSessionsTable extends PowerGridComponent
{
    public string $tableName = 'player-sessions-table';

    public string $sortDirection = 'desc';

    public Player $player;

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(perPage: 5, perPageValues: [5, 10, 25, 50, 100])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Session::query()->where('uuid', $this->player->uuid);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('start', fn (Session $model) => $model->formatStart())
            ->add('end', fn (Session $model) => $model->formatEnd())
            ->add('time', fn (Session $model) => $model->formatTime())
            ->add('ip')
            ->add('version_name', fn (Session $model) => $model->version->name());
    }

    public function columns(): array
    {
        $hideIP = ! auth()->user()->can('show_ip');

        return [
            Column::make(__('player.player.sessions.table.columns.start'), 'start')
                ->sortable()
                ->searchable(),

            Column::make(__('player.player.sessions.table.columns.end'), 'end')
                ->sortable()
                ->searchable(),

            Column::make(__('player.player.sessions.table.columns.time'), 'time')
                ->sortable()
                ->searchable(),

            Column::make(__('player.player.sessions.table.columns.ip'), 'ip')
                ->sortable()
                ->searchable()
                ->hidden($hideIP, $hideIP),

            Column::make(__('player.player.sessions.table.columns.version'), 'version_name', 'version')
                ->sortable()
                ->searchable(),
        ];
    }

    public function placeholder(): string
    {
        return <<<'HTML'
            <div>
                <div class="w-100 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        HTML;
    }
}
