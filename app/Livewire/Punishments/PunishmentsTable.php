<?php

namespace App\Livewire\Punishments;

use App\Models\Punishment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PunishmentsTable extends PowerGridComponent
{
    public string $tableName = 'punishments-table';

    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Punishment::query()->with('player');
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
            ->add('id_label', function ($item) {
                $id = $item->id;
                if ($item->active) {
                    return '<i class="fas fa-xmark-circle fa-lg text-danger"></i> '.$id;
                } else {
                    return '<i class="fas fa-check-circle fa-lg text-success"></i> '.$id;
                }
            })
            ->add('type_name', fn ($item) => $item->type->name())
            ->add('player', function ($item) {
                if ($item->player == null) {
                    return Blade::render('<x-player-link uuid="'.$item->uuid.'" />');
                }
                return Blade::render('<x-player-link uuid="'.$item->uuid.'" username="'.$item->player->username.'" />');
            })
            ->add('punisher', fn ($item) => $item->getPunisherName())
            ->add('time', fn ($item) => $item->getTimeFormatted())
            ->add('end', function (Punishment $model) {
                if ($model->type->isIP()) {
                    return '<span class="badge badge-danger">IP-Ban</span>';
                } elseif ($model->type->isTemporary()) {
                    return '<span class="badge badge-warning" x-data x-tooltip.raw="'.$model->expiresTooltip().'">'.$model->getEndFormatted().'</span>';
                } else {
                    return '<span class="badge badge-danger">Permanent</span>';
                }
            })
            ->add('expires', fn ($item) => $item->expires())
            ->add('reason');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id_label', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'type_name', 'type')
                ->sortable()
                ->searchable(),

            Column::make('Player', 'player', 'uuid')
                ->sortable()
                ->searchable(),

            Column::make('Punisher', 'punisher')
                ->sortable()
                ->searchable(),

            Column::make('Time', 'time')
                ->sortable()
                ->searchable(),

            Column::make('End', 'end')
                ->sortable()
                ->searchable()
                ->hidden(true, false),

            Column::make('Expires', 'expires')
                ->sortable()
                ->searchable()
                ->hidden(true, false),

            Column::make('Reason', 'reason')
                ->sortable()
                ->searchable(),

            Column::action('Action')
                ->headerAttribute('text-center'),
        ];
    }

    public function actions(Punishment $row): array
    {
        return [
            Button::add('info')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#showPunishmentModal'])
                ->slot('<i class="material-icons text-info">info</i>')
                ->can(auth()->user()->can('view_punishments'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('info', ['rowId' => $row->id]),
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editPunishmentModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can(auth()->user()->can('edit_punishments'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deletePunishmentModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_punishments'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->id]),
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
