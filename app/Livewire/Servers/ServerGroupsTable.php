<?php

namespace App\Livewire\Servers;

use App\Models\ServerGroup;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class ServerGroupsTable extends PowerGridComponent
{
    public string $tableName = 'server-groups-table';

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
        return ServerGroup::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('groupname')
            ->add('balancemethodtype')
            ->add('servers', function (ServerGroup $model) {
                return count($model->servers);
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Groupname', 'groupname')
                ->sortable()
                ->searchable(),

            Column::make('Balancemethodtype', 'balancemethodtype')
                ->sortable()
                ->searchable(),

            Column::make('Servers', 'servers')
                ->sortable()
                ->searchable(),

            Column::action('Action')
                ->headerAttribute('text-center'),
        ];
    }

    public function actions(ServerGroup $row): array
    {
        return [
            Button::add('info')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#showServerGroupModal'])
                ->slot('<i class="material-icons text-info">info</i>')
                ->can(auth()->user()->can('view_servers'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('server-group-info', ['rowId' => $row->id]),
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editServerGroupModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can(auth()->user()->can('edit_servers'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('edit-server-group', ['rowId' => $row->id]),
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteServerGroupModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_servers'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete-server-group', ['rowId' => $row->id]),
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
