<?php

namespace App\Livewire\Tickets;

use App\Helpers\TimeUtils;
use App\Models\Tickets\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class TicketsTable extends PowerGridComponent
{
    public string $tableName = 'tickets-table';

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
        return Ticket::query()->with('creatorPlayer');
    }

    public function relationSearch(): array
    {
        return [
            'creatorPlayer' => [
                'username',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id_label', function (Ticket $model) {
                if ($model->active) {
                    return '<i class="fas fa-check-circle fa-lg text-success"></i> '.$model->id;
                } else {
                    return '<i class="fas fa-exclamation-circle fa-lg text-danger"></i> '.$model->id;
                }
            })
            ->add('creator_label', fn(Model $model) => $model->creatorPlayer->username)
            ->add('title')
            ->add('assigned_to', function (Ticket $model) {
                if (empty($model->assigned_to)) {
                    return 'Unassigned';
                } else {
                    return $model->assigned_to;
                }
            })
            ->add('creation_formatted', fn (Ticket $model) => TimeUtils::formatTimestamp($model->creation))
            ->add('last_update_formatted', fn (Ticket $model) => TimeUtils::formatTimestamp($model->last_update))
            ->add('priority_label', fn(Ticket $model) => $model->priority->name())
            ;
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id_label')
                ->sortable()
                ->searchable(),

            Column::make('Creator', 'creator_label')
                ->sortable()
                ->searchable(),

            Column::make('Title', 'title')
                ->sortable()
                ->searchable(),

            Column::make('Assigned To', 'assigned_to')
                ->sortable()
                ->searchable(),

            Column::make('Creation', 'creation_formatted', 'creation')
                ->sortable()
                ->searchable(),

            Column::make('Last update', 'last_update_formatted', 'last_update')
                ->sortable()
                ->searchable(),

            Column::make('Priority', 'priority_label', 'priority')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function actions(Ticket $row): array
    {
        return [
            Button::add('info')
                ->attributes(['data-mdb-ripple-init' => ''])
                ->slot('<i class="material-icons text-info">info</i>')
                ->can(auth()->user()->can('view_tickets'))
                ->id()
                ->class('bg-transparent border-0')
                ->route('tickets.ticket', ['ticket' => $row])
        ];
    }
}
