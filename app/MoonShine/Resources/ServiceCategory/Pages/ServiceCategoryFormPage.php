<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ServiceCategory\Pages;

use App\Models\ServiceCategory;
use App\MoonShine\Resources\ServiceCategory\ServiceCategoryResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends FormPage<ServiceCategoryResource>
 */
class ServiceCategoryFormPage extends FormPage
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
                    'Родительская категория',
                    'parent',
                    formatted: static fn (?ServiceCategory $model): string => $model?->name ?? '',
                    resource: ServiceCategoryResource::class,
                )
                    ->nullable()
                    ->valuesQuery(static fn (Builder $q): Builder => $q->select(['id', 'name'])),
                Text::make('Название', 'name')
                    ->when(
                        fn () => $this->getResource()->isCreateFormPage(),
                        fn (Text $field) => $field->reactive(),
                        fn (Text $field) => $field
                    )
                    ->required(),
                Slug::make('Slug', 'slug')
                    ->unique()
                    ->locked()
                    ->when(
                        fn () => $this->getResource()->isCreateFormPage(),
                        fn (Slug $field) => $field->from('name')->live(),
                        fn (Slug $field) => $field->readonly()
                    ),
                Textarea::make('Описание', 'description')->nullable(),
                Text::make('Иконка', 'icon')->nullable(),
                Number::make('Сортировка', 'sort_order')->default(0)->required(),
                Switcher::make('Активна', 'is_active')->default(true),
                Image::make('Фон хлебных крошек', 'breadcrumb_image')
                    ->disk(moonshineConfig()->getDisk())
                    ->dir('categories')
                    ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                    ->nullable(),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'parent_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('service_categories', 'slug')->ignore($item->getOriginal()?->getKey()),
            ],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'breadcrumb_image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,jpg,png,webp'],
        ];
    }
}
