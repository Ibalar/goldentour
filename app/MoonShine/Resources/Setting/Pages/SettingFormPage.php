<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Setting\Pages;

use App\MoonShine\Resources\Setting\SettingResource;
use Illuminate\Validation\Rule;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends FormPage<SettingResource>
 */
class SettingFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Лейбл', 'label')->required(),
                Text::make('Ключ', 'key')->required(),
                Select::make('Тип', 'type')
                    ->options($this->typeOptions())
                    ->default('string')
                    ->required(),
                Text::make('Группа', 'group')->default('general')->required(),
                Number::make('Сортировка', 'sort_order')->default(0)->required(),
                Textarea::make('Значение', 'value')->nullable(),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'key' => [
                'required',
                'string',
                'max:255',
                Rule::unique('settings', 'key')->ignore($item->getOriginal()?->getKey()),
            ],
            'type' => ['required', 'in:string,number,boolean,json,file,text'],
            'group' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'value' => ['nullable', 'string'],
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
