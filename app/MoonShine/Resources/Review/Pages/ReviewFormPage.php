<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Review\Pages;

use App\Models\Portfolio;
use App\Models\Service;
use App\MoonShine\Resources\Portfolio\PortfolioResource;
use App\MoonShine\Resources\Review\ReviewResource;
use App\MoonShine\Resources\Service\ServiceResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends FormPage<ReviewResource>
 */
class ReviewFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Автор', 'author_name')->required(),
                Phone::make('Телефон', 'author_phone')->nullable(),
                Email::make('E-mail', 'author_email')->nullable(),
                Number::make('Оценка', 'rating')->min(1)->max(5)->default(5)->required(),
                Textarea::make('Текст отзыва', 'text')->required(),
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
                Switcher::make('Опубликован', 'is_published')->default(false),
                Switcher::make('Проверен', 'is_verified')->default(false),
                Textarea::make('Ответ администратора', 'admin_reply')->nullable(),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'author_name' => ['required', 'string', 'max:255'],
            'author_phone' => ['nullable', 'string', 'max:255'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'text' => ['required', 'string'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'portfolio_id' => ['nullable', 'integer', 'exists:portfolios,id'],
            'is_published' => ['boolean'],
            'is_verified' => ['boolean'],
            'admin_reply' => ['nullable', 'string'],
        ];
    }
}
