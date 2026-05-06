<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\PortfolioImage\Pages;

use App\MoonShine\Resources\PortfolioImage\PortfolioImageResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<PortfolioImageResource>
 */
class PortfolioImageIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Number::make('Портфолио ID', 'portfolio_id')->sortable(),
            Image::make('Изображение', 'image')
                ->disk(moonshineConfig()->getDisk()),
            Text::make('Подпись', 'caption'),
            Number::make('Сортировка', 'sort_order')->sortable(),
        ];
    }

    protected function filters(): iterable
    {
        return [
            Number::make('Портфолио ID', 'portfolio_id'),
            Text::make('Подпись', 'caption'),
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
