<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\TeamMember\Pages;

use App\MoonShine\Resources\TeamMember\TeamMemberResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<TeamMemberResource>
 */
class TeamMemberIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('ФИО', 'full_name')->sortable(),
            Text::make('Должность', 'position')->sortable(),
            Image::make('Фото', 'photo')->disk(moonshineConfig()->getDisk()),
            Number::make('Сортировка', 'sort_order')->sortable(),
            Switcher::make('Активен', 'is_active')->sortable(),
        ];
    }

    protected function filters(): iterable
    {
        return [
            Text::make('ФИО', 'full_name'),
            Text::make('Должность', 'position'),
            Switcher::make('Активен', 'is_active'),
        ];
    }

    /**
     * @param  TableBuilder  $component
     */
    protected function modifyListComponent(ComponentContract $component): TableBuilder
    {
        return $component->columnSelection();
    }
}
