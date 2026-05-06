<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Setting\Pages;

use App\MoonShine\Resources\Setting\SettingResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<SettingResource>
 */
class SettingIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Лейбл', 'label')->sortable(),
            Text::make('Ключ', 'key')->sortable(),
            Select::make('Тип', 'type')->options($this->typeOptions())->sortable(),
            Text::make('Группа', 'group')->sortable(),
            Number::make('Сортировка', 'sort_order')->sortable(),
            Text::make('Значение', 'value'),
        ];
    }

    protected function filters(): iterable
    {
        return [
            Text::make('Лейбл', 'label'),
            Text::make('Ключ', 'key'),
            Select::make('Тип', 'type')->options($this->typeOptions()),
            Text::make('Группа', 'group'),
        ];
    }

    /**
     * @param  TableBuilder  $component
     */
    protected function modifyListComponent(ComponentContract $component): TableBuilder
    {
        return $component->columnSelection();
    }

    /**
     * @return array<string, string>
     */
    private function typeOptions(): array
    {
        return [
            'string' => 'Строка',
            'number' => 'Число',
            'boolean' => 'Булево',
            'json' => 'JSON',
            'file' => 'Файл',
            'text' => 'Текст',
        ];
    }
}
