<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Service\Pages;

use App\Models\ServiceCategory;
use App\MoonShine\Resources\Service\ServiceResource;
use App\MoonShine\Resources\ServiceCategory\ServiceCategoryResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends FormPage<ServiceResource>
 */
class ServiceFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                BelongsTo::make(
                    'Категория',
                    'category',
                    formatted: static fn (ServiceCategory $model): string => $model->name,
                    resource: ServiceCategoryResource::class,
                )
                    ->required()
                    ->valuesQuery(static fn (Builder $q): Builder => $q->select(['id', 'name'])),
                Text::make('Название', 'name')->required(),
                Text::make('Slug', 'slug')->required(),
                Textarea::make('Краткое описание', 'short_description')->nullable(),
                Textarea::make('Полное описание', 'full_description')->nullable(),
                Number::make('Цена от', 'price_from')->nullable(),
                Number::make('Цена до', 'price_to')->nullable(),
                Number::make('Площадь от', 'area_from')->nullable(),
                Number::make('Площадь до', 'area_to')->nullable(),
                Text::make('Срок выполнения', 'duration')->nullable(),
                Image::make('Изображение', 'image')
                    ->disk(moonshineConfig()->getDisk())
                    ->dir('services')
                    ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                    ->nullable(),
                Json::make('Галерея', 'gallery')->onlyValue('Изображение')->nullable(),
                Json::make('Особенности', 'features')->onlyValue('Особенность')->nullable(),
                Switcher::make('Активна', 'is_active')->default(true),
                Text::make('Meta title', 'meta_title')->nullable(),
                Textarea::make('Meta description', 'meta_description')->nullable(),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services', 'slug')->ignore($item->getOriginal()?->getKey()),
            ],
            'short_description' => ['nullable', 'string'],
            'full_description' => ['nullable', 'string'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'price_to' => ['nullable', 'numeric', 'min:0'],
            'area_from' => ['nullable', 'integer', 'min:0'],
            'area_to' => ['nullable', 'integer', 'min:0'],
            'duration' => ['nullable', 'string', 'max:255'],
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,jpg,png,webp'],
            'is_active' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ];
    }
}
