<?php

namespace App\Livewire;

use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatType;
use App\Models\Player\Player;
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

final class ChatWithReceiverTable extends PowerGridComponent
{
    public string $tableName = 'chat-with-receiver-table';

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
            ->add('receiver', function (ChatMessage $model) {
                if (is_null($model->receiver)) {
                    $message = $model->message;

                    return strtok($message, ' ');
                } else {
                    // Change this later
                    return Player::getName($model->receiver);
                }
            })
            ->add('type_name', fn (ChatMessage $model) => $model->type->name())
            ->add('message', function (ChatMessage $model) {
                $message = $model->message;

                if (is_null($model->receiver) && ($model->type === ChatType::PM || $model->type === ChatType::FRIENDS)) {
                    $receiver = strtok($message, ' ');

                    return Str::replaceFirst($receiver, '', $message);
                } else {
                    return $message;
                }
            })
            ->add('server')
            ->add('time_formatted', fn (ChatMessage $model) => Carbon::createFromTimestampMs($model->time, config('app.timezone', 'UTC'))->format('Y-m-d H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('Player', 'player', 'uuid')
                ->sortable()
                ->searchable(),

            Column::make('Receiver', 'receiver', 'receiver'),

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
            Filter::inputText('player')
                ->filterRelation('player', 'username'),
            Filter::inputText('server'),
        ];
    }
}
