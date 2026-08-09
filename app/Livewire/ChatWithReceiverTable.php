<?php

namespace App\Livewire;

use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatType;
use App\Models\Player\Player;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Livewire\Attributes\Modelable;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class ChatWithReceiverTable extends PowerGridComponent
{
    public string $tableName = 'chat-with-receiver-table';

    public string $sortDirection = 'desc';

    #[Modelable]
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
        return ChatMessage::query()
            ->select(['id', 'uuid', 'receiver', 'type', 'message', 'server', 'time'])
            ->with('player', fn ($query) => $query->select('uuid', 'username'))
            ->with('receiverPlayer', fn ($query) => $query->select('uuid', 'username'))
            ->where('type', $this->type);
    }

    public function relationSearch(): array
    {
        return [
            'player' => [
                'username',
            ],
            'receiverPlayer' => [
                'username',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('player', fn (ChatMessage $model) => Blade::render('<x-player-link uuid="'.$model->uuid.'" username="'.$model->player->username.'" />'))
            ->add('receiverPlayer', function (ChatMessage $model) {
                if (is_null($model->receiver)) {
                    $username = strtok($model->message, ' ');
                    $uuid = Player::getUUID($username);

                    if ($uuid) {
                        return Blade::render('<x-player-link uuid="'.$uuid.'" username="'.$username.'" />');
                    }

                    return $username;
                } else {
                    return Blade::render('<x-player-link uuid="'.$model->receiver.'" username="'.$model->receiverPlayer->username.'" />');
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

            Column::make('Receiver', 'receiverPlayer', 'receiver')
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
            Filter::inputText('player')
                ->filterRelation('player', 'username'),
            Filter::inputText('receiver')
                ->filterRelation('receiverPlayer', 'username'),
            Filter::inputText('server'),
        ];
    }
}
