<?php

namespace App\Livewire\Languages;

use App\Models\Language;
use App\Models\LanguageMessage;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class LanguageMessagesTable extends PowerGridComponent
{
    public string $tableName = 'language-messages-table';

    public bool $showFilters = true;

    public Language $language;

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return LanguageMessage::query()->where('language_id', $this->language->id);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('key')
            ->add('message')
            ->add('plugin');
    }

    public function columns(): array
    {
        return [
            Column::make('Key', 'key')
                ->sortable()
                ->searchable(),

            Column::make('Message', 'message')
                ->sortable()
                ->searchable()
                ->editOnClick(hasPermission: auth()->user()->can('edit_languages')),

            Column::make('Plugin', 'plugin')
                ->sortable()
                ->searchable()
                ->hidden(isForceHidden: false),
        ];
    }

    public function filters(): array {
        return [
            Filter::select('plugin')
                ->dataSource(LanguageMessage::query()->select('plugin')->distinct()->get())
                ->optionLabel('plugin')
                ->optionValue('plugin')
        ];
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        LanguageMessage::query()->find($id)->update([$field => e($value)]);
    }
}
