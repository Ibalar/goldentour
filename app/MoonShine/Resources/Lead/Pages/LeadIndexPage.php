<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Lead\Pages;

use App\Models\Service;
use App\MoonShine\Resources\Lead\LeadResource;
use App\MoonShine\Resources\Service\ServiceResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<LeadResource>
 */
class LeadIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'name')->sortable(),
            Phone::make('Телефон', 'phone')->sortable(),
            Text::make('E-mail', 'email'),
            BelongsTo::make(
                'Услуга',
                'service',
                formatted: static fn (Service $model): string => $model->name,
                resource: ServiceResource::class,
            )->nullable(),
            Select::make('Источник', 'source')
                ->options($this->sourceOptions())
                ->sortable(),
            Select::make('Статус', 'status')
                ->options($this->statusOptions())
                ->sortable(),
            Number::make('Цена', 'calculated_price'),
            Date::make('Создана', 'created_at')->format('d.m.Y H:i')->sortable(),
        ];
    }

    protected function filters(): iterable
    {
        return [
            Text::make('Имя', 'name'),
            Phone::make('Телефон', 'phone'),
            BelongsTo::make(
                'Услуга',
                'service',
                formatted: static fn (Service $model): string => $model->name,
                resource: ServiceResource::class,
            )
                ->nullable()
                ->valuesQuery(static fn (Builder $q): Builder => $q->select(['id', 'name'])),
            Select::make('Источник', 'source')->options($this->sourceOptions()),
            Select::make('Статус', 'status')->options($this->statusOptions()),
        ];
    }

    /**
     * @param  TableBuilder  $component
     */
    protected function modifyListComponent(ComponentContract $component): TableBuilder
    {
        return $component->columnSelection();
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
