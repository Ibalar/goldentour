<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Service\Pages;

use App\Models\ServiceCategory;
use App\MoonShine\Resources\Service\ServiceResource;
use App\MoonShine\Resources\ServiceCategory\ServiceCategoryResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<ServiceResource>
 */
class ServiceIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(
                'Категория',
                'category',
                formatted: static fn (ServiceCategory $model): string => $model->name,
                resource: ServiceCategoryResource::class,
            ),
            Text::make('Название', 'name')->sortable(),
            Text::make('Slug', 'slug')->sortable(),
            Number::make('Цена от', 'price_from')->sortable(),
            Number::make('Цена до', 'price_to')->sortable(),
            Switcher::make('Активна', 'is_active')->sortable(),
        ];
    }

    protected function filters(): iterable
    {
        return [
            BelongsTo::make(
                'Категория',
                'category',
                formatted: static fn (ServiceCategory $model): string => $model->name,
                resource: ServiceCategoryResource::class,
            )->valuesQuery(static fn (Builder $q): Builder => $q->select(['id', 'name'])),
            Text::make('Название', 'name'),
            Text::make('Slug', 'slug'),
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
