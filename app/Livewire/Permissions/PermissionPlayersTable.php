<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\PermissionPlayer;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PermissionPlayersTable extends PowerGridComponent
{
    public string $tableName = 'permission-players-table';

    public string $primaryKey = 'uuid';

    public string $sortField = 'uuid';

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
        return PermissionPlayer::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('uuid')
            ->add('name')
            ->add('prefix')
            ->add('suffix');
    }

    public function columns(): array
    {
        return [
            Column::make('UUID', 'uuid')
                ->sortable()
                ->searchable(),

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Prefix', 'prefix')
                ->sortable()
                ->searchable(),

            Column::make('Suffix', 'suffix')
                ->sortable()
                ->searchable(),

            Column::action('Action'),
        ];
    }

    public function actions(PermissionPlayer $row): array
    {
        return [
            Button::add('info')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#showPermissionPlayerModal'])
                ->slot('<i class="material-icons text-info">info</i>')
                ->id()
                ->class('bg-transparent border-0')
                ->dispatchTo('permissions.show-players', 'info', ['rowId' => $row->uuid]),
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editPermissionPlayerModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can(auth()->user()->can('edit_permissions'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatchTo('permissions.show-players', 'edit', ['rowId' => $row->uuid]),
        ];
    }
}
