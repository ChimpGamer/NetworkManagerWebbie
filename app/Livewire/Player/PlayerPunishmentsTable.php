<?php

namespace App\Livewire\Player;

use App\Models\Player\Player;
use App\Models\Player\Session;
use App\Models\Punishment;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PlayerPunishmentsTable extends PowerGridComponent
{
    public string $tableName = 'player-punishments-table';

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
        return Punishment::query()
            ->where('uuid', $this->player->uuid)
            ->where('type', '!=', 20)
            ->where('type', '!=', 21);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('type_name', fn (Punishment $model) => $model->type->name())
            ->add('punisher_name', fn (Punishment $model) => $model->getPunisherName())
            ->add('reason', fn (Punishment $model) => $model->reason)
            ->add('time', fn (Punishment $model) => $model->getTimeFormatted());
    }

    public function columns(): array
    {
        return [
            Column::make(__('player.player.punishments.table.columns.type'), 'type_name', 'type')
                ->sortable()
                ->searchable(),

            Column::make(__('player.player.punishments.table.columns.punisher'), 'punisher_name', 'punisher')
                ->sortable()
                ->searchable(),

            Column::make(__('player.player.punishments.table.columns.reason'), 'reason')
                ->sortable()
                ->searchable(),

            Column::make(__('player.player.punishments.table.columns.time'), 'time')
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
