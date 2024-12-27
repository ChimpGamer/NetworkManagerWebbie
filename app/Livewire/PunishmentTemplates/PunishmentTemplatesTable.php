<?php

namespace App\Livewire\PunishmentTemplates;

use App\Models\PunishmentTemplate;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PunishmentTemplatesTable extends PowerGridComponent
{
    public string $tableName = 'punishment-templates-table';

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
        return PunishmentTemplate::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('type_name', fn ($item) => $item->type->name)
            ->add('duration_formatted', function (PunishmentTemplate $model) {
                if ($model->duration <= 0) {
                    return 'Permanent';
                }
                return $model->duration / 1000;
            })
            ->add('server')
            ->add('reason');
    }

    public function columns(): array
    {
        $canEdit = auth()->user()->can('edit_pre_punishments');

        return [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Type', 'type_name', 'type')
                ->searchable()
                ->sortable(),

            Column::make('Duration', 'duration_formatted', 'duration')
                ->searchable()
                ->sortable(),
            Column::make('Server', 'server')
                ->searchable()
                ->sortable(),
            Column::make('Reason', 'reason')
                ->searchable()
                ->sortable(),

            Column::action('Action')
                ->headerAttribute('text-center')
                ->hidden(! $canEdit, ! $canEdit),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name'),
            Filter::inputText('reason'),
        ];
    }

    public function actions(PunishmentTemplate $row): array
    {
        return [
            Button::add('info')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#showPunishmentTemplateModal'])
                ->slot('<i class="material-icons text-info">info</i>')
                ->can(auth()->user()->can('edit_pre_punishments'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('info', ['rowId' => $row->id]),
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editPunishmentTemplateModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can(auth()->user()->can('edit_pre_punishments'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deletePunishmentTemplateModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_pre_punishments'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->id]),
        ];
    }

    /*
    public function actionRules(PunishmentTemplate $row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
