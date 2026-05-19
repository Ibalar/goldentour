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
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\TinyMce\Fields\TinyMce;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
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
            Tabs::make([
                Tab::make('Основное', [
                    Box::make([
                        ID::make(),

                        Grid::make([
                            Column::make([
                                BelongsTo::make(
                                    'Категория',
                                    'category',
                                    formatted: static fn (ServiceCategory $model): string => $model->name,
                                    resource: ServiceCategoryResource::class,
                                )
                                    ->required()
                                    ->valuesQuery(static fn (Builder $q): Builder => $q->select(['id', 'name'])),
                            ], colSpan: 6),

                            Column::make([
                                Text::make('Название', 'name')
                                    ->when(
                                        fn () => $this->getResource()->isCreateFormPage(),
                                        fn (Text $field) => $field->reactive(),
                                        fn (Text $field) => $field
                                    )
                                    ->required(),
                            ], colSpan: 6),
                        ]),

                        Grid::make([
                            Column::make([
                                Slug::make('Slug', 'slug')
                                    ->unique()
                                    ->locked()
                                    ->when(
                                        fn () => $this->getResource()->isCreateFormPage(),
                                        fn (Slug $field) => $field->from('name')->live(),
                                        fn (Slug $field) => $field->readonly()
                                    ),
                            ], colSpan: 6),

                            Column::make([
                                Text::make('Срок выполнения', 'duration')->nullable(),
                            ], colSpan: 6),
                        ]),

                        Grid::make([
                            Column::make([
                                Number::make('Цена от', 'price_from')->nullable(),
                            ], colSpan: 3),

                            Column::make([
                                Number::make('Цена до', 'price_to')->nullable(),
                            ], colSpan: 3),

                            Column::make([
                                Number::make('Площадь от', 'area_from')->nullable(),
                            ], colSpan: 3),

                            Column::make([
                                Number::make('Площадь до', 'area_to')->nullable(),
                            ], colSpan: 3),
                        ]),

                        Grid::make([
                            Column::make([
                                Image::make('Изображение', 'image')
                                    ->disk(moonshineConfig()->getDisk())
                                    ->dir('services')
                                    ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                                    ->nullable(),
                            ], colSpan: 6),

                            Column::make([
                                Image::make('Фон шапки (breadcrumb)', 'breadcrumb_image')
                                    ->disk(moonshineConfig()->getDisk())
                                    ->dir('services/breadcrumbs')
                                    ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                                    ->nullable(),
                            ], colSpan: 6),
                        ]),

                        Grid::make([
                            Column::make([
                                Switcher::make('Активна', 'is_active')->default(true),
                                Switcher::make('В меню', 'show_in_menu')->default(false),
                                Switcher::make('На главной', 'show_on_home')->default(false),
                            ], colSpan: 12),
                        ]),
                    ]),
                ]),

                Tab::make('Описание', [
                    Box::make([
                        Textarea::make('Краткое описание', 'short_description')->nullable(),
                        TinyMce::make('Полное описание', 'full_description')
                            ->nullable()
                            ->addOption('file_manager', 'laravel-filemanager'),

                        Grid::make([
                            Column::make([
                                Json::make('Галерея', 'gallery')->onlyValue('Изображение')->nullable(),
                            ], colSpan: 6),

                            Column::make([
                                Json::make('Особенности', 'features')->onlyValue('Особенность')->nullable(),
                            ], colSpan: 6),
                        ]),
                    ]),
                ]),

                Tab::make('Дополнительные блоки', [
                    Tabs::make([
                        Tab::make('Почему выбирают', [
                            Box::make([
                                Grid::make([
                                    Column::make([
                                        Text::make('Заголовок', 'why_choose_title')->nullable(),
                                    ], colSpan: 6),
                                    Column::make([
                                        Textarea::make('Подзаголовок', 'why_choose_subtitle')->nullable(),
                                    ], colSpan: 6),
                                ]),
                                Json::make('Элементы', 'why_choose_items')
                                    ->fields([
                                        Text::make('Иконка', 'icon')->nullable()->placeholder('icon-about-item-1.svg'),
                                        Text::make('Заголовок', 'title'),
                                        TinyMce::make('Описание', 'description'),
                                    ])
                                    ->creatable()
                                    ->removable()
                                    ->vertical()
                                    ->nullable(),
                            ]),
                        ]),

                        Tab::make('Что входит', [
                            Box::make([
                                Grid::make([
                                    Column::make([
                                        Text::make('Заголовок', 'offer_title')->nullable(),
                                    ], colSpan: 6),
                                    Column::make([
                                        Textarea::make('Подзаголовок', 'offer_subtitle')->nullable(),
                                    ], colSpan: 6),
                                ]),
                                Json::make('Список', 'offer_list')
                                    ->onlyValue('Текст')
                                    ->creatable()
                                    ->removable()
                                    ->vertical()
                                    ->nullable(),
                                Json::make('Блоки с изображениями', 'offer_items')
                                    ->fields([
                                        Image::make('Изображение', 'image')
                                            ->disk(moonshineConfig()->getDisk())
                                            ->dir('services/offers')
                                            ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                                            ->nullable(),
                                        Text::make('Заголовок', 'title'),
                                        Textarea::make('Описание', 'description'),
                                    ])
                                    ->creatable()
                                    ->removable()
                                    ->vertical()
                                    ->nullable(),
                            ]),
                        ]),

                        Tab::make('Процесс работы', [
                            Box::make([
                                Grid::make([
                                    Column::make([
                                        Text::make('Заголовок', 'process_title')->nullable(),
                                    ], colSpan: 6),
                                    Column::make([
                                        Textarea::make('Подзаголовок', 'process_subtitle')->nullable(),
                                    ], colSpan: 6),
                                ]),
                                Json::make('Элементы процесса', 'process_items')
                                    ->fields([
                                        Text::make('Иконка', 'icon')->nullable()->placeholder('icon-what-we-do-item-1.svg'),
                                        Text::make('Заголовок', 'title'),
                                        Textarea::make('Описание', 'description'),
                                    ])
                                    ->creatable()
                                    ->removable()
                                    ->vertical()
                                    ->nullable(),
                                Image::make('Изображение процесса', 'process_image')
                                    ->disk(moonshineConfig()->getDisk())
                                    ->dir('services/process')
                                    ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                                    ->nullable(),
                            ]),
                        ]),

                        Tab::make('FAQ', [
                            Box::make([
                                Text::make('Заголовок блока', 'faq_title')->nullable(),
                                Json::make('Вопросы и ответы', 'faq_items')
                                    ->fields([
                                        Text::make('Вопрос', 'question'),
                                        TinyMce::make('Ответ', 'answer'),
                                    ])
                                    ->creatable()
                                    ->removable()
                                    ->vertical()
                                    ->nullable(),
                            ]),
                        ]),

                        Tab::make('Тарифы', [
                            Box::make([
                                Grid::make([
                                    Column::make([
                                        Text::make('Заголовок блока', 'pricing_title')->nullable(),
                                    ], colSpan: 6),
                                    Column::make([
                                        Textarea::make('Подзаголовок', 'pricing_subtitle')->nullable(),
                                    ], colSpan: 6),
                                ]),
                                Json::make('Колонки тарифов', 'pricing_plans')
                                    ->fields([
                                        Text::make('Название тарифа', 'name'),
                                        Text::make('Цена', 'price')->placeholder('от 945 руб за 1м²'),
                                        Switcher::make('Выделить', 'highlighted')->default(false),
                                    ])
                                    ->creatable()
                                    ->removable()
                                    ->vertical()
                                    ->nullable(),
                                Json::make('Строки таблицы', 'pricing_features')
                                    ->fields([
                                        Text::make('Название работы', 'name'),
                                        Json::make('Значения по колонкам', 'values')
                                            ->onlyValue('Значение (+, - или текст)')
                                            ->creatable()
                                            ->removable()
                                            ->nullable(),
                                    ])
                                    ->creatable()
                                    ->removable()
                                    ->vertical()
                                    ->nullable(),
                            ]),
                        ]),
                    ])->vertical(),
                ]),

                Tab::make('SEO', [
                    Box::make([
                        Text::make('Meta title', 'meta_title')->nullable(),
                        Textarea::make('Meta description', 'meta_description')->nullable(),
                        Text::make('Meta keywords', 'meta_keywords')->nullable(),
                    ]),
                ]),
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
            'breadcrumb_image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,jpg,png,webp'],
            'is_active' => ['boolean'],
            'show_in_menu' => ['boolean'],
            'show_on_home' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'why_choose_title' => ['nullable', 'string', 'max:255'],
            'why_choose_subtitle' => ['nullable', 'string'],
            'why_choose_items' => ['nullable', 'array'],
            'offer_title' => ['nullable', 'string', 'max:255'],
            'offer_subtitle' => ['nullable', 'string'],
            'offer_list' => ['nullable', 'array'],
            'offer_items' => ['nullable', 'array'],
            'process_title' => ['nullable', 'string', 'max:255'],
            'process_subtitle' => ['nullable', 'string'],
            'process_items' => ['nullable', 'array'],
            'process_image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,jpg,png,webp'],
            'faq_title' => ['nullable', 'string', 'max:255'],
            'faq_items' => ['nullable', 'array'],
            'pricing_title' => ['nullable', 'string', 'max:255'],
            'pricing_subtitle' => ['nullable', 'string'],
            'pricing_plans' => ['nullable', 'array'],
            'pricing_features' => ['nullable', 'array'],
        ];
    }
}
