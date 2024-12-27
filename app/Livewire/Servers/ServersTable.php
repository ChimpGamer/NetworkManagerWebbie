<?php

namespace App\Livewire\Servers;

use App\Models\Server;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class ServersTable extends PowerGridComponent
{
    public string $tableName = 'servers-table';

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
        return Server::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('servername')
            ->add('displayname', fn (Server $model) => $model->displayname)
            ->add('ip')
            ->add('port')
            ->add('online_label', function (Server $model) {
                if ($model->online) {
                    return '<span class="badge badge-success">Online</span>';
                } else {
                    return '<span class="badge badge-danger">Offline</span>';
                }
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Servername', 'servername')
                ->sortable()
                ->searchable(),

            Column::make('Displayname', 'displayname')
                ->sortable()
                ->searchable(),

            Column::make('Ip', 'ip')
                ->sortable()
                ->searchable(),

            Column::make('Port', 'port')
                ->sortable()
                ->searchable(),

            Column::make('Online', 'online_label', 'online')
                ->sortable()
                ->searchable(),

            Column::action('Action')
                ->headerAttribute('text-center'),
        ];
    }

    public function actions(Server $row): array
    {
        return [
            Button::add('info')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#showServerModal'])
                ->slot('<i class="material-icons text-info">info</i>')
                ->can(auth()->user()->can('view_servers'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('info', ['rowId' => $row->id]),
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editServerModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can(auth()->user()->can('edit_servers'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteServerModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_servers'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->id]),
        ];
    }
}
