<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Lead\Pages;

use App\Models\Service;
use App\MoonShine\Resources\Lead\LeadResource;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\Service\ServiceResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Models\MoonshineUser;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends FormPage<LeadResource>
 */
class LeadFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Имя', 'name')->required(),
                Phone::make('Телефон', 'phone')->required(),
                Email::make('E-mail', 'email')->nullable(),
                Textarea::make('Сообщение', 'message')->nullable(),
                BelongsTo::make(
                    'Услуга',
                    'service',
                    formatted: static fn (Service $model): string => $model->name,
                    resource: ServiceResource::class,
                )
                    ->nullable()
                    ->valuesQuery(static fn (Builder $q): Builder => $q->select(['id', 'name'])),
                Number::make('Рассчитанная цена', 'calculated_price')->nullable(),
                Number::make('Рассчитанная площадь', 'calculated_area')->nullable(),
                Select::make('Источник', 'source')
                    ->options($this->sourceOptions())
                    ->default('form')
                    ->required(),
                Select::make('Статус', 'status')
                    ->options($this->statusOptions())
                    ->default('new')
                    ->required(),
                Text::make('IP адрес', 'ip_address')->nullable(),
                Textarea::make('User Agent', 'user_agent')->nullable(),
                Text::make('UTM Source', 'utm_source')->nullable(),
                Text::make('UTM Medium', 'utm_medium')->nullable(),
                Text::make('UTM Campaign', 'utm_campaign')->nullable(),
                Date::make('Обработана', 'processed_at')->withTime()->nullable(),
                BelongsTo::make(
                    'Обработал',
                    'processor',
                    formatted: static fn (MoonshineUser $model): string => $model->name,
                    resource: MoonShineUserResource::class,
                )
                    ->nullable()
                    ->valuesQuery(static fn (Builder $q): Builder => $q->select(['id', 'name'])),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'calculated_price' => ['nullable', 'numeric', 'min:0'],
            'calculated_area' => ['nullable', 'integer', 'min:0'],
            'source' => ['required', 'in:form,calculator,direct,phone'],
            'status' => ['required', 'in:new,processing,completed,cancelled'],
            'ip_address' => ['nullable', 'string', 'max:45'],
            'user_agent' => ['nullable', 'string'],
            'utm_source' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:100'],
            'processed_at' => ['nullable', 'date'],
            'processed_by' => ['nullable', 'integer', 'exists:moonshine_users,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function sourceOptions(): array
    {
        return [
            'form' => 'Форма',
            'calculator' => 'Калькулятор',
            'direct' => 'Прямое обращение',
            'phone' => 'Телефон',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function statusOptions(): array
    {
        return [
            'new' => 'Новая',
            'processing' => 'В обработке',
            'completed' => 'Завершена',
            'cancelled' => 'Отменена',
        ];
    }
}
