<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\GroupMember;
use App\Models\Permissions\PlayerPermission;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PermissionPlayerPermissionsTable extends PowerGridComponent
{
    public string $tableName = 'permission-player-permissions-table';

    public string $uuid;

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
        return PlayerPermission::query()->where('playeruuid', $this->uuid);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('permission')
            ->add('server', fn (PlayerPermission $model) => empty($model->server) ? 'ALL' : $model->server)
            ->add('world', fn (PlayerPermission $model) => empty($model->world) ? 'ALL' : $model->world)
            ->add('expires')
            ->add('expires_label', fn (PlayerPermission $model) => empty($model->expires) ? 'Never' : $model->expires);
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Permission', 'permission')
                ->sortable()
                ->searchable(),

            Column::make('Server', 'server')
                ->sortable()
                ->searchable(),

            Column::make('World', 'world')
                ->sortable()
                ->searchable(),

            Column::make('Expires', 'expires_label', 'expires')
                ->sortable()
                ->searchable(),

            Column::action('Action'),
        ];
    }

    public function actions(PlayerPermission $row): array
    {
        return [
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editPlayerPermissionModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can(auth()->user()->can('edit_permissions'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deletePlayerPermissionModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_permissions'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->id]),
        ];
    }
}
