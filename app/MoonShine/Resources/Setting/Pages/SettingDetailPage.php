<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Setting\Pages;

use App\MoonShine\Resources\Setting\SettingResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends DetailPage<SettingResource>
 */
class SettingDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Лейбл', 'label'),
            Text::make('Ключ', 'key'),
            Select::make('Тип', 'type')->options($this->typeOptions()),
            Text::make('Группа', 'group'),
            Number::make('Сортировка', 'sort_order'),
            Textarea::make('Значение', 'value'),
            Date::make('Создана', 'created_at')->format('d.m.Y H:i'),
            Date::make('Обновлена', 'updated_at')->format('d.m.Y H:i'),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function typeOptions(): array
    {
        return [
            'string' => 'Строка',
            'number' => 'Число',
            'boolean' => 'Булево',
            'json' => 'JSON',
            'file' => 'Файл',
            'text' => 'Текст',
        ];
    }
}
