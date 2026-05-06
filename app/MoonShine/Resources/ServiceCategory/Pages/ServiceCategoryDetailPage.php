<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ServiceCategory\Pages;

use App\MoonShine\Resources\ServiceCategory\ServiceCategoryResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends DetailPage<ServiceCategoryResource>
 */
class ServiceCategoryDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Название', 'name'),
            Text::make('Slug', 'slug'),
            Textarea::make('Описание', 'description'),
            Text::make('Иконка', 'icon'),
            Number::make('Сортировка', 'sort_order'),
            Switcher::make('Активна', 'is_active'),
            Date::make('Создано', 'created_at')->format('d.m.Y H:i'),
            Date::make('Обновлено', 'updated_at')->format('d.m.Y H:i'),
        ];
    }
}
