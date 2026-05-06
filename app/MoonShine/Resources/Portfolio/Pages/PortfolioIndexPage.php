<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Portfolio\Pages;

use App\Models\Service;
use App\MoonShine\Resources\Portfolio\PortfolioResource;
use App\MoonShine\Resources\Service\ServiceResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<PortfolioResource>
 */
class PortfolioIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'title')->sortable(),
            Text::make('Slug', 'slug')->sortable(),
            BelongsTo::make(
                'Услуга',
                'service',
                formatted: static fn (Service $model): string => $model->name,
                resource: ServiceResource::class,
            )->nullable(),
            Text::make('Клиент', 'client_name'),
            Date::make('Дата завершения', 'completion_date')->format('d.m.Y'),
            Number::make('Площадь', 'area')->sortable(),
            Text::make('Локация', 'location'),
            Switcher::make('Рекомендуемое', 'is_featured')->sortable(),
            Switcher::make('Активно', 'is_active')->sortable(),
        ];
    }

    protected function filters(): iterable
    {
        return [
            Text::make('Название', 'title'),
            Text::make('Slug', 'slug'),
            BelongsTo::make(
                'Услуга',
                'service',
                formatted: static fn (Service $model): string => $model->name,
                resource: ServiceResource::class,
            )
                ->nullable()
                ->valuesQuery(static fn (Builder $q): Builder => $q->select(['id', 'name'])),
            Switcher::make('Рекомендуемое', 'is_featured'),
            Switcher::make('Активно', 'is_active'),
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
