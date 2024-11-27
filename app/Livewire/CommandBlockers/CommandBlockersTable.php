<?php

namespace App\Livewire\CommandBlockers;

use App\Models\CommandBlocker;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class CommandBlockersTable extends PowerGridComponent
{
    public string $tableName = 'command-blockers-table';

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
        return CommandBlocker::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id_label', function (CommandBlocker $model) {
                $id = $model->id;
                if ($model->enabled) {
                    return '<i class="fas fa-check-circle fa-lg text-success"></i> '.$id;
                } else {
                    return '<i class="fas fa-xmark-circle fa-lg text-danger"></i> '.$id;
                }
            })
            ->add('name')
            ->add('command')
            ->add('server')
            ->add('bypasspermission_label', function (CommandBlocker $model) {
                if ($model->bypasspermission) {
                    return '<i class="fas fa-check-circle fa-lg text-success"></i>';
                } else {
                    return '<i class="fas fa-xmark-circle fa-lg text-danger"></i>';
                }
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Command', 'command')
                ->sortable()
                ->searchable(),

            Column::make('Server', 'server')
                ->sortable()
                ->searchable(),

            Column::make('Bypasspermission', 'bypasspermission_label', 'bypasspermission')
                ->sortable()
                ->searchable(),

            Column::action('Action')
                ->headerAttribute('text-center'),
        ];
    }

    public function actions(CommandBlocker $row): array
    {
        return [
            Button::add('info')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#showCommandBlockerModal'])
                ->slot('<i class="material-icons text-info">info</i>')
                ->can(auth()->user()->can('view_commandblocker'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('info', ['rowId' => $row->id]),
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editCommandBlockerModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can(auth()->user()->can('edit_commandblocker'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteCommandBlockerModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_commandblocker'))
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
