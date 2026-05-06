<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Review\Pages;

use App\Models\Portfolio;
use App\Models\Service;
use App\MoonShine\Resources\Portfolio\PortfolioResource;
use App\MoonShine\Resources\Review\ReviewResource;
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
 * @extends IndexPage<ReviewResource>
 */
class ReviewIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Автор', 'author_name')->sortable(),
            Number::make('Оценка', 'rating')->sortable(),
            BelongsTo::make(
                'Услуга',
                'service',
                formatted: static fn (Service $model): string => $model->name,
                resource: ServiceResource::class,
            )->nullable(),
            BelongsTo::make(
                'Портфолио',
                'portfolio',
                formatted: static fn (Portfolio $model): string => $model->title,
                resource: PortfolioResource::class,
            )->nullable(),
            Switcher::make('Опубликован', 'is_published')->sortable(),
            Switcher::make('Проверен', 'is_verified')->sortable(),
            Date::make('Создан', 'created_at')->format('d.m.Y H:i')->sortable(),
        ];
    }

    protected function filters(): iterable
    {
        return [
            Text::make('Автор', 'author_name'),
            Number::make('Оценка', 'rating'),
            BelongsTo::make(
                'Услуга',
                'service',
                formatted: static fn (Service $model): string => $model->name,
                resource: ServiceResource::class,
            )
                ->nullable()
                ->valuesQuery(static fn (Builder $q): Builder => $q->select(['id', 'name'])),
            BelongsTo::make(
                'Портфолио',
                'portfolio',
                formatted: static fn (Portfolio $model): string => $model->title,
                resource: PortfolioResource::class,
            )
                ->nullable()
                ->valuesQuery(static fn (Builder $q): Builder => $q->select(['id', 'title'])),
            Switcher::make('Опубликован', 'is_published'),
            Switcher::make('Проверен', 'is_verified'),
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
