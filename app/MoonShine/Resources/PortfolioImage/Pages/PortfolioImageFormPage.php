<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\PortfolioImage\Pages;

use App\MoonShine\Resources\PortfolioImage\PortfolioImageResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends FormPage<PortfolioImageResource>
 */
class PortfolioImageFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Number::make('Портфолио ID', 'portfolio_id')->required(),
                Image::make('Изображение', 'image')
                    ->disk(moonshineConfig()->getDisk())
                    ->dir('portfolio-images')
                    ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                    ->nullable(),
                Text::make('Подпись', 'caption')->nullable(),
                Number::make('Сортировка', 'sort_order')->default(0)->required(),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'portfolio_id' => ['required', 'integer', 'exists:portfolios,id'],
            'image' => [
                ...$item->getKey() !== null ? ['sometimes', 'nullable'] : ['required'],
                'image',
                'mimes:jpeg,jpg,png,webp',
            ],
            'caption' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
