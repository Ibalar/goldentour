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
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\TinyMce\Fields\TinyMce;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Json;
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

                Grid::make([
                    Column::make([
                        Text::make('Название', 'title')
                            ->when(
                                fn () => $this->getResource()->isCreateFormPage(),
                                fn (Text $field) => $field->reactive(),
                                fn (Text $field) => $field
                            )
                            ->required(),
                    ], colSpan: 6),

                    Column::make([
                        Slug::make('Slug', 'slug')
                            ->unique()
                            ->locked()
                            ->when(
                                fn () => $this->getResource()->isCreateFormPage(),
                                fn (Slug $field) => $field->from('title')->live(),
                                fn (Slug $field) => $field->readonly()
                            ),
                    ], colSpan: 6),
                ]),

                TinyMce::make('Описание', 'description')->nullable(),

                Grid::make([
                    Column::make([
                        Text::make('Клиент', 'client_name')->nullable(),
                    ], colSpan: 6),
                    Column::make([
                        Date::make('Дата завершения', 'completion_date')->nullable(),
                    ], colSpan: 6),
                ]),

                Grid::make([
                    Column::make([
                        Number::make('Площадь', 'area')->nullable(),
                    ], colSpan: 6),
                    Column::make([
                        Text::make('Локация', 'location')->nullable(),
                    ], colSpan: 6),
                ]),

                BelongsTo::make(
                    'Услуга',
                    'service',
                    formatted: static fn (Service $model): string => $model->name,
                    resource: ServiceResource::class,
                )
                    ->nullable()
                    ->valuesQuery(static fn (Builder $q): Builder => $q->select(['id', 'name'])),

                Grid::make([
                    Column::make([
                        Image::make('Превью', 'thumbnail')
                            ->disk(moonshineConfig()->getDisk())
                            ->dir('portfolio')
                            ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                            ->nullable(),
                    ], colSpan: 4),
                    Column::make([
                        Image::make('Фото до', 'before_image')
                            ->disk(moonshineConfig()->getDisk())
                            ->dir('portfolio')
                            ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                            ->nullable(),
                    ], colSpan: 4),
                    Column::make([
                        Image::make('Фото после', 'after_image')
                            ->disk(moonshineConfig()->getDisk())
                            ->dir('portfolio')
                            ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                            ->nullable(),
                    ], colSpan: 4),
                ]),

                Json::make('Галерея', 'gallery')
                    ->fields([
                        Image::make('Изображение', 'image')
                            ->disk(moonshineConfig()->getDisk())
                            ->dir('portfolio/gallery')
                            ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                            ->nullable(),
                        Text::make('Подпись', 'caption')->nullable(),
                    ])
                    ->creatable()
                    ->removable()
                    ->vertical()
                    ->nullable(),

                Grid::make([
                    Column::make([
                        Switcher::make('Рекомендуемое', 'is_featured')->default(false),
                        Switcher::make('Активно', 'is_active')->default(true),
                    ], colSpan: 12),
                ]),

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
            'gallery' => ['nullable', 'array'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ];
    }
}
