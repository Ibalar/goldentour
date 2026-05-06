<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Lead\Pages;

use App\Models\Service;
use App\MoonShine\Resources\Lead\LeadResource;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\Service\ServiceResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Models\MoonshineUser;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends DetailPage<LeadResource>
 */
class LeadDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Имя', 'name'),
            Phone::make('Телефон', 'phone'),
            Email::make('E-mail', 'email'),
            Textarea::make('Сообщение', 'message'),
            BelongsTo::make(
                'Услуга',
                'service',
                formatted: static fn (Service $model): string => $model->name,
                resource: ServiceResource::class,
            )->nullable(),
            Number::make('Рассчитанная цена', 'calculated_price'),
            Number::make('Рассчитанная площадь', 'calculated_area'),
            Select::make('Источник', 'source')->options($this->sourceOptions()),
            Select::make('Статус', 'status')->options($this->statusOptions()),
            Text::make('IP адрес', 'ip_address'),
            Textarea::make('User Agent', 'user_agent'),
            Text::make('UTM Source', 'utm_source'),
            Text::make('UTM Medium', 'utm_medium'),
            Text::make('UTM Campaign', 'utm_campaign'),
            Date::make('Обработана', 'processed_at')->format('d.m.Y H:i'),
            BelongsTo::make(
                'Обработал',
                'processor',
                formatted: static fn (MoonshineUser $model): string => $model->name,
                resource: MoonShineUserResource::class,
            )->nullable(),
            Date::make('Создана', 'created_at')->format('d.m.Y H:i'),
            Date::make('Обновлена', 'updated_at')->format('d.m.Y H:i'),
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
