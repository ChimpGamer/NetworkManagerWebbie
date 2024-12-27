<?php

namespace App\Livewire;

use App\Models\HelpOP;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class HelpOPTable extends PowerGridComponent
{
    public string $tableName = 'help-op-table';

    public string $sortDirection = 'desc';

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
        return HelpOP::query()
            ->with('player')
            ->selectRaw('nm_helpop.id, nm_helpop.requester, nm_helpop.message, nm_helpop.server, FROM_UNIXTIME(nm_helpop.time / 1000) as time');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('requester_label', fn (HelpOP $model) => Blade::render('<x-player-link uuid="'.$model->requester.'" username="'.$model->player->username.'" />'))
            ->add('message')
            ->add('server')
            ->add('time_formatted', fn (HelpOP $model) => Carbon::parse($model->time, config('app.timezone', 'UTC'))->format('Y-m-d H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Requester', 'requester_label', 'requester')
                ->searchable()
                ->sortable(),

            Column::make('Message', 'message')
                ->searchable()
                ->sortable(),

            Column::make('Server', 'server')
                ->searchable()
                ->sortable(),

            Column::make('Time', 'time_formatted', 'time')
                ->searchable(),

            Column::action('Action'),
        ];
    }

    public function actions(HelpOP $row): array
    {
        return [
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteHelpOPModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_helpop'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['rowId' => $row->id]),
        ];
    }
}
