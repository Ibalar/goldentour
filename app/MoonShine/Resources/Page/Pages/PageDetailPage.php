<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Page\Pages;

use App\MoonShine\Resources\Page\PageResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends DetailPage<PageResource>
 */
class PageDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Заголовок', 'title'),
            Text::make('Slug', 'slug'),
            Textarea::make('Контент', 'content'),
            Text::make('Шаблон', 'template'),
            Switcher::make('Активна', 'is_active'),
            Text::make('Meta title', 'meta_title'),
            Textarea::make('Meta description', 'meta_description'),
            Date::make('Создана', 'created_at')->format('d.m.Y H:i'),
            Date::make('Обновлена', 'updated_at')->format('d.m.Y H:i'),
        ];
    }
}
