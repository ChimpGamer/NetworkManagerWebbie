<?php

namespace App\Livewire\Permissions;

use App\Models\Permissions\Group;
use App\Models\Permissions\GroupParent;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class GroupParentsTable extends PowerGridComponent
{
    public string $tableName = 'group-parents-table';

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
        return GroupParent::query()->where('groupid', $this->groupId);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('parent_group', fn (GroupParent $model) => $model->parentGroup->name);
    }

    public function columns(): array
    {
        return [
            Column::make('Parent Group', 'parent_group')
                ->sortable()
                ->searchable(),

            Column::action('Action'),
        ];
    }

    public function actions(GroupParent $row): array
    {
        return [
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteGroupParentModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_permissions'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->id]),
        ];
    }
}
