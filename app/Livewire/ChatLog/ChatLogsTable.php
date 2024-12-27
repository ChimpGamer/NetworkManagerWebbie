<?php

namespace App\Livewire\ChatLog;

use App\Models\Chat\ChatLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

class ChatLogsTable extends PowerGridComponent
{
    public string $tableName = 'chat-logs-table';

    public ?string $primaryKeyAlias = 'uuid';

    public string $sortField = 'chatlogs.time';

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
        return ChatLog::query()
            ->with(['creatorPlayer', 'trackedPlayer'])
            ->selectRaw('nm_chatlogs.uuid, nm_chatlogs.creator, nm_chatlogs.tracked, nm_chatlogs.server, FROM_UNIXTIME(nm_chatlogs.time / 1000) as time');
    }

    public function relationSearch(): array
    {
        return [
            'creatorPlayer' => [
                'username',
            ],
            'trackedPlayer' => [
                'username',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('uuid_label', function (ChatLog $model) {
                return sprintf(
                    '<a href="/chatlogs/%s">%s</a>',
                    urlencode(e($model->uuid)),
                    e($model->uuid)
                );
            })
            ->add('creator_label', function (ChatLog $model) {
                return Blade::render('<x-player-link uuid="'.$model->creator.'" username="'.$model->creatorPlayer->username.'" />');
            })
            ->add('tracked_label', function (ChatLog $model) {
                return Blade::render('<x-player-link uuid="'.$model->tracked.'" username="'.$model->trackedPlayer->username.'" />');
            })
            ->add('server')
            ->add('time_formatted', fn (ChatLog $model) => Carbon::parse($model->time, config('app.timezone', 'UTC'))->format('Y-m-d H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'uuid_label', 'uuid')
                ->sortable()
                ->searchable(),

            Column::make('Creator', 'creator_label', 'creator')
                ->sortable()
                ->searchable(),

            Column::make('Tracked', 'tracked_label', 'tracked')
                ->sortable()
                ->searchable(),

            Column::make('Server', 'server')
                ->sortable()
                ->searchable(),

            Column::make('Time', 'time_formatted', 'time')
                ->sortable()
                ->searchableRaw('DATE_FORMAT(FROM_UNIXTIME(time/ 1000), "%Y-%m-%d") like ?'),

            Column::action('Action')
                ->headerAttribute('text-center'),
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

    public function actions(ChatLog $row): array
    {
        return [
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteChatlogModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_chatlog'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->uuid]),
        ];
    }
}
