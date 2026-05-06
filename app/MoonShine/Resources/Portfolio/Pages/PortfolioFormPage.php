<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Portfolio\Pages;

use App\Models\Service;
use App\MoonShine\Resources\Portfolio\PortfolioResource;
use App\MoonShine\Resources\Service\ServiceResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends FormPage<PortfolioResource>
 */
class PortfolioFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Название', 'title')->required(),
                Text::make('Slug', 'slug')->required(),
                Textarea::make('Описание', 'description')->nullable(),
                Text::make('Клиент', 'client_name')->nullable(),
                Date::make('Дата завершения', 'completion_date')->nullable(),
                Number::make('Площадь', 'area')->nullable(),
                Text::make('Локация', 'location')->nullable(),
                BelongsTo::make(
                    'Услуга',
                    'service',
                    formatted: static fn (Service $model): string => $model->name,
                    resource: ServiceResource::class,
                )
                    ->nullable()
                    ->valuesQuery(static fn (Builder $q): Builder => $q->select(['id', 'name'])),
                Image::make('Превью', 'thumbnail')
                    ->disk(moonshineConfig()->getDisk())
                    ->dir('portfolio')
                    ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                    ->nullable(),
                Image::make('Фото до', 'before_image')
                    ->disk(moonshineConfig()->getDisk())
                    ->dir('portfolio')
                    ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                    ->nullable(),
                Image::make('Фото после', 'after_image')
                    ->disk(moonshineConfig()->getDisk())
                    ->dir('portfolio')
                    ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                    ->nullable(),
                Switcher::make('Рекомендуемое', 'is_featured')->default(false),
                Switcher::make('Активно', 'is_active')->default(true),
                Text::make('Meta title', 'meta_title')->nullable(),
                Textarea::make('Meta description', 'meta_description')->nullable(),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('portfolios', 'slug')->ignore($item->getOriginal()?->getKey()),
            ],
            'description' => ['nullable', 'string'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'completion_date' => ['nullable', 'date'],
            'area' => ['nullable', 'integer', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'thumbnail' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,jpg,png,webp'],
            'before_image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,jpg,png,webp'],
            'after_image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,jpg,png,webp'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ];
    }
}
