<?php

namespace Addons\UltimateJQMessages\Livewire;

use Addons\UltimateJQMessages\App\Models\JoinQuitMessage;
use Addons\UltimateTags\App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class JoinQuitMessagesTable extends PowerGridComponent
{
    public string $tableName = 'join-quit-messages-table';

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
        return JoinQuitMessage::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('type_name', fn (JoinQuitMessage $model) => $model->type->name())
            ->add('message_formatted', fn (JoinQuitMessage $model) => $model->message)
            ->add('description')
            ->add('permission')
            ->add('server');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'type_name', 'type')
                ->sortable()
                ->searchable(),

            Column::make('Message', 'message_formatted', 'message')
                ->sortable()
                ->searchable(),

            Column::make('Permission', 'permission')
                ->sortable()
                ->searchable(),

            Column::action('Action')
                ->headerAttribute('text-center'),
        ];
    }

    public function actions(JoinQuitMessage $row): array
    {
        return [
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editJQMessageModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                //->can(auth()->user()->can('edit_tags'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteJQMessageModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                //->can(auth()->user()->can('edit_tags'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->id]),
        ];
    }
}
