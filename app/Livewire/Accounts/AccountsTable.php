<?php

namespace App\Livewire\Accounts;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class AccountsTable extends PowerGridComponent
{
    public string $tableName = 'accounts-table';

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
        return User::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('username')
            ->add('usergroup');
    }

    public function columns(): array
    {
        return [
            Column::make('User', 'username')
                ->searchable()
                ->sortable(),

            Column::make('Group', 'usergroup')
                ->searchable()
                ->sortable(),

            Column::action('Action')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),
        ];
    }

    public function actions(User $row): array
    {
        return [
            Button::add('info')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#showAccountModal'])
                ->slot('<i class="material-icons text-info">info</i>')
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('info', ['rowId' => $row->id]),
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editAccountModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can(auth()->user()->can('manage_groups_and_accounts'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteAccountModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('manage_groups_and_accounts'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->id]),
        ];
    }
}
