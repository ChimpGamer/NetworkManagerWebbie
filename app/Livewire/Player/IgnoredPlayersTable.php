<?php

namespace App\Livewire\Player;

use App\Models\Player\IgnoredPlayer;
use App\Models\Player\Player;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class IgnoredPlayersTable extends PowerGridComponent
{
    public string $tableName = 'ignored-players-table';

    public ?string $primaryKeyAlias = 'uuid';

    public string $sortField = 'ignored_name';

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
        return IgnoredPlayer::query()->where('uuid', $this->player->uuid);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('ignored_name');
    }

    public function columns(): array
    {
        return [
            Column::make(__('player.player.ignored-players.table.columns.ignored-player'), 'ignored_name'),
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
