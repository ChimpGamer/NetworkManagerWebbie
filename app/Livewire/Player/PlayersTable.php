<?php

namespace App\Livewire\Player;

use App\Helpers\TimeUtils;
use App\Models\Player\Player;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PlayersTable extends PowerGridComponent
{
    public string $tableName = 'players-table';

    public ?string $primaryKeyAlias = 'uuid';

    public string $sortField = 'firstlogin';

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
        return Player::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('uuid', function ($item) {
                return Blade::render('<x-player-link uuid="'.$item->uuid.'" username="'.$item->username.'" />');
            })
            ->add('firstlogin', function ($item) {
                return TimeUtils::formatTimestamp($item->firstlogin);
            })
            ->add('lastlogin', function ($item) {
                return TimeUtils::formatTimestamp($item->lastlogin);
            })
            ->add('online')
            ->add('online_label', function ($item) {
                if ($item->online) {
                    return '<i class="fas fa-check-circle fa-lg text-success"></i>';
                } else {
                    return '<i class="fas fa-xmark-circle fa-lg text-danger"></i>';
                }
            });
    }

    public function columns(): array
    {
        return [
            Column::make(__('player.players.table.columns.player'), 'uuid')
                ->sortable()
                ->searchable()
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),

            Column::make('player_name', 'username')
                ->searchable()
                ->hidden(),
            Column::make('player_ip', 'ip')
                ->searchable()
                ->hidden(),

            Column::make(__('player.players.table.columns.first-login'), 'firstlogin')
                ->sortable()
                ->searchable()
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),

            Column::make(__('player.players.table.columns.last-login'), 'lastlogin')
                ->sortable()
                ->searchable()
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),

            Column::make(__('player.players.table.columns.online'), 'online_label', 'online')
                ->sortable()
                ->searchable()
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),
        ];
    }
}
