<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Portfolio\Pages;

use App\Models\Service;
use App\MoonShine\Resources\Portfolio\PortfolioResource;
use App\MoonShine\Resources\Service\ServiceResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends DetailPage<PortfolioResource>
 */
class PortfolioDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Название', 'title'),
            Text::make('Slug', 'slug'),
            Textarea::make('Описание', 'description'),
            Text::make('Клиент', 'client_name'),
            Date::make('Дата завершения', 'completion_date')->format('d.m.Y'),
            Number::make('Площадь', 'area'),
            Text::make('Локация', 'location'),
            BelongsTo::make(
                'Услуга',
                'service',
                formatted: static fn (Service $model): string => $model->name,
                resource: ServiceResource::class,
            )->nullable(),
            Image::make('Превью', 'thumbnail')->disk(moonshineConfig()->getDisk()),
            Image::make('Фото до', 'before_image')->disk(moonshineConfig()->getDisk()),
            Image::make('Фото после', 'after_image')->disk(moonshineConfig()->getDisk()),
            Switcher::make('Рекомендуемое', 'is_featured'),
            Switcher::make('Активно', 'is_active'),
            Text::make('Meta title', 'meta_title'),
            Textarea::make('Meta description', 'meta_description'),
            Date::make('Создано', 'created_at')->format('d.m.Y H:i'),
            Date::make('Обновлено', 'updated_at')->format('d.m.Y H:i'),
        ];
    }
}
