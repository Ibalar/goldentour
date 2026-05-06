<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Service\Pages;

use App\Models\ServiceCategory;
use App\MoonShine\Resources\Service\ServiceResource;
use App\MoonShine\Resources\ServiceCategory\ServiceCategoryResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends DetailPage<ServiceResource>
 */
class ServiceDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make(
                'Категория',
                'category',
                formatted: static fn (ServiceCategory $model): string => $model->name,
                resource: ServiceCategoryResource::class,
            ),
            Text::make('Название', 'name'),
            Text::make('Slug', 'slug'),
            Textarea::make('Краткое описание', 'short_description'),
            Textarea::make('Полное описание', 'full_description'),
            Number::make('Цена от', 'price_from'),
            Number::make('Цена до', 'price_to'),
            Number::make('Площадь от', 'area_from'),
            Number::make('Площадь до', 'area_to'),
            Text::make('Срок выполнения', 'duration'),
            Image::make('Изображение', 'image')
                ->disk(moonshineConfig()->getDisk()),
            Json::make('Галерея', 'gallery')->onlyValue('Изображение'),
            Json::make('Особенности', 'features')->onlyValue('Особенность'),
            Switcher::make('Активна', 'is_active'),
            Text::make('Meta title', 'meta_title'),
            Textarea::make('Meta description', 'meta_description'),
            Date::make('Создано', 'created_at')->format('d.m.Y H:i'),
            Date::make('Обновлено', 'updated_at')->format('d.m.Y H:i'),
        ];
    }
}
