<?php

namespace App\Livewire\Languages;

use App\Models\Language;
use App\Models\Value;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class LanguagesTable extends PowerGridComponent
{
    public string $tableName = 'languages-table';

    public ?Language $defaultLanguage = null;

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
        return Language::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name');
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

            Column::action('Action')
                ->headerAttribute('text-center'),
        ];
    }

    public function actions(Language $row): array
    {
        return [
            Button::add('edit')
                ->attributes(['data-mdb-ripple-init' => ''])
                ->slot('<i class="material-icons text-warning">edit</i>')
                ->can(auth()->user()->can('edit_languages'))
                ->id()
                ->class('bg-transparent border-0')
                ->route('languages.show', ['language' => $row]),
            Button::add('delete')
                ->attributes(['data-mdb-ripple-init' => '', 'data-mdb-modal-init' => '', 'data-mdb-target' => '#deleteLanguageModal'])
                ->slot('<i class="material-icons text-danger">delete</i>')
                ->can(auth()->user()->can('edit_languages'))
                ->id()
                ->class('bg-transparent border-0')
                ->dispatch('delete', ['language' => $row])
        ];
    }

    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('delete')
                ->when(fn($row) => $this->isProtectedLanguage($row))
                ->hide(),
        ];
    }

    private function isProtectedLanguage(Language $language): bool
    {
        if ($this->defaultLanguage === null) {
            $this->defaultLanguage = Language::getByName(Value::getValueByVariable('setting_language_default')->value);
        }

        if ($language->id === 1) {
            return true;
        }

        return $this->defaultLanguage?->id == $language->id;
    }
}
