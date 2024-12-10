<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\GroupMember;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PermissionPlayerGroupsTable extends PowerGridComponent
{
    public string $tableName = 'permission-player-groups-table';

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
        return GroupMember::query()->where('playeruuid', $this->uuid);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('group', fn (GroupMember $model) => $model->group->name)
            ->add('server', fn (GroupMember $model) => empty($model->server) ? 'ALL' : $model->server)
            ->add('expires', fn (GroupMember $model) => empty($model->expires) ? 'Never' : $model->expires);
    }

    public function columns(): array
    {
        return [
            Column::make('Group', 'group')
                ->sortable()
                ->searchable(),

            Column::make('Server', 'server')
                ->sortable()
                ->searchable(),

            Column::make('Expires', 'expires')
                ->sortable()
                ->searchable(),

            Column::action('Action'),
        ];
    }

    public function actions(GroupMember $row): array
    {
        return [
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editPlayerGroupModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can(auth()->user()->can('edit_permissions'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deletePlayerGroupModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_permissions'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->id]),
        ];
    }
}
