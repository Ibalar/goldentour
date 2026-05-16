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
                Switcher::make('Показывать в меню', 'show_in_menu')->default(false),
                Text::make('Meta title', 'meta_title')->nullable(),
                Textarea::make('Meta description', 'meta_description')->nullable(),
            ]),

            Tabs::make([
                Tab::make('Почему выбирают', [
                    Text::make('Заголовок', 'why_choose_title')->nullable(),
                    Textarea::make('Подзаголовок', 'why_choose_subtitle')->nullable(),
                    Json::make('Элементы', 'why_choose_items')
                        ->fields([
                            Text::make('Иконка', 'icon')->nullable(),
                            Text::make('Заголовок', 'title'),
                            Textarea::make('Описание', 'description'),
                        ])
                        ->creatable()
                        ->removable()
                        ->nullable(),
                ]),

                Tab::make('Что входит', [
                    Text::make('Заголовок', 'offer_title')->nullable(),
                    Textarea::make('Подзаголовок', 'offer_subtitle')->nullable(),
                    Json::make('Список преимуществ', 'offer_list')
                        ->onlyValue('Текст')
                        ->creatable()
                        ->removable()
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
                        ->nullable(),
                ]),

                Tab::make('Процесс работы', [
                    Text::make('Заголовок', 'process_title')->nullable(),
                    Textarea::make('Подзаголовок', 'process_subtitle')->nullable(),
                    Json::make('Элементы процесса', 'process_items')
                        ->fields([
                            Text::make('Иконка', 'icon')->nullable(),
                            Text::make('Заголовок', 'title'),
                            Textarea::make('Описание', 'description'),
                        ])
                        ->creatable()
                        ->removable()
                        ->nullable(),
                    Image::make('Изображение процесса', 'process_image')
                        ->disk(moonshineConfig()->getDisk())
                        ->dir('services/process')
                        ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                        ->nullable(),
                ]),

                Tab::make('Что важно знать (FAQ)', [
                    Text::make('Заголовок', 'faq_title')->nullable(),
                    Json::make('Вопросы и ответы', 'faq_items')
                        ->fields([
                            Text::make('Вопрос', 'question'),
                            Textarea::make('Ответ', 'answer'),
                        ])
                        ->creatable()
                        ->removable()
                        ->nullable(),
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
            'is_active' => ['boolean'],
            'show_in_menu' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
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
        ];
    }
}
