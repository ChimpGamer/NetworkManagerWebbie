<?php

namespace App\Livewire\Player;

use App\Models\Player\Friend;
use App\Models\Player\Player;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PlayerFriendsTable extends PowerGridComponent
{
    public string $tableName = 'player-friends-table';

    // public ?string $primaryKeyAlias = 'uuid';

    public string $sortField = 'time';

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
        return Friend::query()
            ->select('username', 'time')
            ->join('players', function (JoinClause $join) {
                $join->on('friend1_uuid', '=', 'uuid')
                    ->where('friend2_uuid', '=', $this->player->uuid)
                    ->orOn('friend2_uuid', '=', 'uuid')
                    ->where('friend1_uuid', '=', $this->player->uuid);
            });
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('username')
            ->add('time');
    }

    public function columns(): array
    {
        return [
            Column::make(__('player.player.friends.table.columns.friend'), 'username')
                ->sortable(),
            Column::make(__('player.player.friends.table.columns.since'), 'time')
                ->sortable(),
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
