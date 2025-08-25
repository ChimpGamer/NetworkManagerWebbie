<?php

namespace App\Livewire\Accounts;

use App\Models\Group;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class AccountGroupsTable extends PowerGridComponent
{
    public string $tableName = 'account-groups-table';

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
        return Group::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name');
    }

    public function columns(): array
    {
        return [
            Column::make('Group', 'name')
                ->searchable()
                ->sortable(),

            Column::action('Action')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),
        ];
    }

    public function actions(Group $row): array
    {
        return [
            Button::add('edit-group')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editAccountGroupModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can('manage_groups_and_accounts')
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('edit-group', ['rowId' => $row->id]),
            Button::add('delete-group')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteAccountGroupModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can('manage_groups_and_accounts')
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete-group', ['rowId' => $row->id]),
        ];
    }
}
