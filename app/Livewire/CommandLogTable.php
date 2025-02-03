<?php

namespace App\Livewire;

use App\Models\CommandLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

class CommandLogTable extends PowerGridComponent
{
    public string $tableName = 'command-log-table';

    public string $sortField = 'command_log.time';

    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return CommandLog::query()
            ->with('player')
            ->selectRaw('nm_command_log.uuid, nm_command_log.command, nm_command_log.server, FROM_UNIXTIME(nm_command_log.time / 1000) as time');
    }

    public function relationSearch(): array
    {
        return [
            'player' => [
                'username',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('username', function (CommandLog $model) {
                return Blade::render('<x-player-link uuid="'.$model->uuid.'" username="'.$model->player->username.'" />');
            })
            ->add('command')
            ->add('server')
            ->add('time_formatted', fn (CommandLog $model) => Carbon::parse($model->time, config('app.timezone', 'UTC'))->format('Y-m-d H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make(__('command-log.table.columns.username'), 'username')
                ->sortable()
                ->searchable(),

            Column::make(__('command-log.table.columns.command'), 'command')
                ->sortable()
                ->searchable(),

            Column::make(__('command-log.table.columns.server'), 'server')
                ->sortable()
                ->searchable(),

            Column::make(__('command-log.table.columns.time'), 'time_formatted', 'time')
                ->sortable()
                ->searchableRaw('DATE_FORMAT(FROM_UNIXTIME(time/ 1000), "%Y-%m-%d") like ?'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('server'),
        ];
    }

    /*public function filters(): array
    {
        return [
            Filter::datetimepicker('time_formatted', 'time')
                ->params([
                    'timezone' => config('app.timezone', 'UTC'),
                ]),
        ];
    }*/
}
