<?php

namespace App\Livewire\Analytics;

use App\Models\Player\Player;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class MostPlayedVersionsTable extends PowerGridComponent
{
    public string $tableName = 'most-played-versions-table';

    public bool $deferLoading = true;

    public string $loadingComponent = 'components.loading-placeholder';

    public function setUp(): array
    {
        return [];
    }

    public function datasource(): Collection
    {
        return Player::query()->selectRaw('DISTINCT(version) as version, count(*) AS count, COUNT(*) * 100.0 / sum(COUNT(*)) over() as percentage')
            ->orderBy('count', 'desc')
            ->groupBy('version')
            ->get();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('version_name', fn ($model) => $model->version->name())
            ->add('count')
            ->add('percentage_formatted', fn ($model) => number_format($model->percentage, 2, '.', ' '));
    }

    public function columns(): array
    {
        return [
            Column::make('Version', 'version_name', 'version')
                ->sortable(),

            Column::make('Players', 'count')
                ->sortable(),

            Column::make('Percentage', 'percentage_formatted', 'percentage')
                ->sortable(),
        ];
    }
}
