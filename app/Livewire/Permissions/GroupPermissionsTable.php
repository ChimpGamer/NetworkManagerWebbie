<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\GroupPermission;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class GroupPermissionsTable extends PowerGridComponent
{
    public string $tableName = 'group-permissions-table';

    public int $groupId;

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
        return GroupPermission::query()->where('groupid', $this->groupId);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('permission')
            ->add('server', fn ($model) => empty($model->server) ? 'ALL' : $model->server)
            ->add('world', fn ($model) => empty($model->world) ? 'ALL' : $model->world)
            ->add('expires')
            ->add('expires_label', fn ($model) => empty($model->expires) ? 'Never' : $model->expires);
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

    public function actions(GroupPermission $row): array
    {
        return [
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editGroupPermissionModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can(auth()->user()->can('edit_permissions'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteGroupPermissionModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_permissions'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->id]),
        ];
    }
}
