<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Page\Pages;

use App\MoonShine\Resources\Page\PageResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<PageResource>
 */
class PageIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Заголовок', 'title')->sortable(),
            Text::make('Slug', 'slug')->sortable(),
            Text::make('Шаблон', 'template')->sortable(),
            Switcher::make('Активна', 'is_active')->sortable(),
            Date::make('Обновлена', 'updated_at')->format('d.m.Y H:i')->sortable(),
        ];
    }

    protected function filters(): iterable
    {
        return [
            Text::make('Заголовок', 'title'),
            Text::make('Slug', 'slug'),
            Text::make('Шаблон', 'template'),
            Switcher::make('Активна', 'is_active'),
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
