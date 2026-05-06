<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\PortfolioImage\Pages;

use App\MoonShine\Resources\PortfolioImage\PortfolioImageResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends DetailPage<PortfolioImageResource>
 */
class PortfolioImageDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Number::make('Портфолио ID', 'portfolio_id'),
            Image::make('Изображение', 'image')
                ->disk(moonshineConfig()->getDisk()),
            Text::make('Подпись', 'caption'),
            Number::make('Сортировка', 'sort_order'),
            Date::make('Создано', 'created_at')->format('d.m.Y H:i'),
            Date::make('Обновлено', 'updated_at')->format('d.m.Y H:i'),
        ];
    }
}
