<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\TeamMember\Pages;

use App\MoonShine\Resources\TeamMember\TeamMemberResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends FormPage<TeamMemberResource>
 */
class TeamMemberFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('ФИО', 'full_name')->required(),
                Text::make('Должность', 'position')->required(),
                Image::make('Фото', 'photo')
                    ->disk(moonshineConfig()->getDisk())
                    ->dir('team')
                    ->allowedExtensions(['jpg', 'jpeg', 'png', 'webp'])
                    ->nullable(),
                Textarea::make('Биография', 'bio')->nullable(),
                Phone::make('Телефон', 'phone')->nullable(),
                Email::make('E-mail', 'email')->nullable(),
                Number::make('Сортировка', 'sort_order')->default(0)->required(),
                Switcher::make('Активен', 'is_active')->default(true),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'photo' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,jpg,png,webp'],
            'bio' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
