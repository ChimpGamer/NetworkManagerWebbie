<?php

namespace App\Livewire;

use App\Helpers\TimeUtils;
use App\Models\Chat\ChatMessage;
use App\Models\CommandLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Livewire\Attributes\Reactive;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

class CommandLogTable extends PowerGridComponent
{
    public string $tableName = 'command-log-table';

    /*public bool $showFilters = true;*/

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
            ->join('players', 'command_log.uuid', 'players.uuid')
            ->selectRaw('nm_command_log.uuid, nm_command_log.command, nm_command_log.server, FROM_UNIXTIME(nm_command_log.time / 1000) as time, nm_players.username');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('username', function ($command) {
                return Blade::render('<x-player-link uuid="' . $command->uuid . '" username="' . $command->username . '" />');
            })
            ->add('command')
            ->add('server')
            ->add('time_formatted', fn ($command) => Carbon::parse($command->time, config('app.timezone', 'UTC'))->format('Y-m-d H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('Username', 'username')
                ->sortable()
                ->searchable(),

            Column::make('Command', 'command')
                ->sortable()
                ->searchable(),

            Column::make('Server', 'server')
                ->sortable()
                ->searchable(),

            Column::make('Time', 'time_formatted', 'command_log.time')
                ->sortable()
                ->searchableRaw('DATE_FORMAT(FROM_UNIXTIME(time/ 1000), "%Y-%m-%d") like ?'),
        ];
    }

    /*public function filters(): array
    {
        return [
            Filter::datetimepicker('time_formatted', 'command_log.time')
            ->params([
                'timezone' => config('app.timezone', 'UTC')
            ]),
        ];
    }*/
}
