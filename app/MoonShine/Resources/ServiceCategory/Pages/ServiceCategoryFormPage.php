<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ServiceCategory\Pages;

use App\MoonShine\Resources\ServiceCategory\ServiceCategoryResource;
use Illuminate\Validation\Rule;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
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
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
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
        ];
    }
}
