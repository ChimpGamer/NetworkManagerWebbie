<?php

namespace App\Livewire\Announcements;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class AnnouncementsTable extends PowerGridComponent
{
    public string $tableName = 'announcements-table';

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
        return Announcement::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('message', fn (Announcement $model) => str($model->message)->limit(140))
            ->add('expires_label', function (Announcement $model) {
                if ($model->expires != null) {
                    return '<i class="fas fa-check-circle fa-lg text-success" x-data x-tooltip.raw="'.$model->expires.'"></i>';
                } else {
                    return '<i class="fas fa-xmark-circle fa-lg text-danger"></i>';
                }
            })
            ->add('active_label', function (Announcement $model) {
                if ($model->active) {
                    return '<i class="fas fa-check-circle fa-lg text-success"></i>';
                } else {
                    return '<i class="fas fa-xmark-circle fa-lg text-danger"></i>';
                }
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Message', 'message')
                ->sortable()
                ->searchable(),

            Column::make('Expires', 'expires_label', 'expires')
                ->sortable(),

            Column::make('Active', 'active_label', 'active')
                ->sortable()
                ->searchable(),

            Column::action('Action'),
        ];
    }

    public function actions(Announcement $row): array
    {
        return [
            Button::add('info')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#showAnnouncementModal'])
                ->slot('<i class="material-icons text-info">info</i>')
                ->can(auth()->user()->can('view_announcements'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('info', ['rowId' => $row->id]),
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#editAnnouncementModal'])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can(auth()->user()->can('edit_announcements'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteAnnouncementModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_announcements'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->id]),
        ];
    }

    /*
    public function actionRules($row): array
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
