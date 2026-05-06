<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Review\Pages;

use App\Models\Portfolio;
use App\Models\Service;
use App\MoonShine\Resources\Portfolio\PortfolioResource;
use App\MoonShine\Resources\Review\ReviewResource;
use App\MoonShine\Resources\Service\ServiceResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends DetailPage<ReviewResource>
 */
class ReviewDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Автор', 'author_name'),
            Phone::make('Телефон', 'author_phone'),
            Email::make('E-mail', 'author_email'),
            Number::make('Оценка', 'rating'),
            Textarea::make('Текст отзыва', 'text'),
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
            Switcher::make('Опубликован', 'is_published'),
            Switcher::make('Проверен', 'is_verified'),
            Textarea::make('Ответ администратора', 'admin_reply'),
            Date::make('Создан', 'created_at')->format('d.m.Y H:i'),
            Date::make('Обновлен', 'updated_at')->format('d.m.Y H:i'),
        ];
    }
}
