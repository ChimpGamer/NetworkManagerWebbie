<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\GroupMember;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class GroupMembersTable extends PowerGridComponent
{
    public string $tableName = 'group-members-table';

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
        return GroupMember::query()->with('permissionPlayer');
    }

    public function relationSearch(): array
    {
        return [
            'permissionPlayer' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('username', fn ($model) => $model->permissionPlayer->name)
            ->add('server', fn ($model) => empty($model->server) ? 'ALL' : $model->server)
            ->add('expires', fn ($model) => empty($model->expired) ? 'Never' : $model->expired);
    }

    public function columns(): array
    {
        return [
            Column::make('Username', 'username')
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
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteGroupMemberModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_permissions'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->id]),
        ];
    }
}
