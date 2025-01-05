<?php

namespace App\Livewire;

use App\Models\Chat\ChatMessage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\Reactive;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class ChatTable extends PowerGridComponent
{
    public string $tableName = 'chat-table';

    public string $sortDirection = 'desc';

    #[Reactive]
    public int $type;

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
        return ChatMessage::query()->with('player')->where('type', $this->type);
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
            ->add('player', fn (ChatMessage $model) => Blade::render('<x-player-link uuid="'.$model->uuid.'" username="'.$model->player->username.'" />'))
            ->add('type_name', fn (ChatMessage $model) => $model->type->name())
            ->add('message')
            ->add('server')
            ->add('time_formatted', fn (ChatMessage $model) => Carbon::createFromTimestampMs($model->time, config('app.timezone', 'UTC'))->format('Y-m-d H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('Player', 'player', 'uuid')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'type_name', 'type')
                ->sortable()
                ->searchable(),

            Column::make('Message', 'message')
                ->sortable()
                ->searchable(),

            Column::make('Server', 'server')
                ->sortable()
                ->searchable(),

            Column::make('Time', 'time_formatted', 'time')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('server'),
        ];
    }
}
