<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\TeamMember\Pages;

use App\MoonShine\Resources\TeamMember\TeamMemberResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends DetailPage<TeamMemberResource>
 */
class TeamMemberDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('ФИО', 'full_name'),
            Text::make('Должность', 'position'),
            Image::make('Фото', 'photo')->disk(moonshineConfig()->getDisk()),
            Textarea::make('Биография', 'bio'),
            Phone::make('Телефон', 'phone'),
            Email::make('E-mail', 'email'),
            Number::make('Сортировка', 'sort_order'),
            Switcher::make('Активен', 'is_active'),
            Date::make('Создан', 'created_at')->format('d.m.Y H:i'),
            Date::make('Обновлен', 'updated_at')->format('d.m.Y H:i'),
        ];
    }
}
