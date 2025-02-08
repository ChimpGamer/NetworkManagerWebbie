<?php

namespace App\Livewire\Analytics;

use App\Models\Player\Login;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class MostUsedVirtualHostsTable extends PowerGridComponent
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
        return Login::query()->selectRaw('vhost, COUNT(DISTINCT uuid, vhost) as count, COUNT(DISTINCT uuid, vhost) * 100.0 / sum(COUNT(DISTINCT uuid, vhost)) over() as percentage')
            ->groupBy('vhost')
            ->orderBy('count', 'desc')
            ->get();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('vhost', fn ($model) => $model->vhost ?? 'Unknown')
            ->add('count')
            ->add('percentage_formatted', fn ($model) => number_format($model->percentage, 2, '.', ' '));
    }

    public function columns(): array
    {
        return [
            Column::make('Virtual Host', 'vhost')
                ->sortable(),

            Column::make('Players', 'count')
                ->sortable(),

            Column::make('Percentage', 'percentage_formatted', 'percentage')
                ->sortable(),
        ];
    }
}
