# 05. Этап 2 — Административная панель MoonShine

## 2.1 Ресурсы MoonShine

### Создание ресурсов

```bash
php artisan moonshine:resource ServiceCategoryResource --model=ServiceCategory
php artisan moonshine:resource ServiceResource --model=Service
php artisan moonshine:resource PortfolioResource --model=Portfolio
php artisan moonshine:resource ReviewResource --model=Review
php artisan moonshine:resource LeadResource --model=Lead
php artisan moonshine:resource TeamMemberResource --model=TeamMember
php artisan moonshine:resource SettingResource --model=Setting
php artisan moonshine:resource PageResource --model=Page
```

### app/MoonShine/Resources/ServiceCategoryResource.php

```php
<?php

namespace App\MoonShine\Resources;

use App\Models\ServiceCategory;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class ServiceCategoryResource extends ModelResource
{
    protected string $model = ServiceCategory::class;
    
    protected string $title = 'Категории услуг';

    protected bool $createInModal = false;
    
    protected bool $editInModal = false;

    public function fields(): array
    {
        return [
            Box::make('Основная информация', [
                ID::make()->sortable(),
                
                Text::make('Название', 'name')
                    ->required(),
                
                Text::make('Slug', 'slug')
                    ->required()
                    ->hint('URL-идентификатор категории'),
                
                Textarea::make('Описание', 'description')
                    ->nullable(),
                
                Text::make('Иконка', 'icon')
                    ->nullable()
                    ->hint('CSS класс иконки или путь к файлу'),
                
                Number::make('Порядок сортировки', 'sort_order')
                    ->default(0)
                    ->buttons()
                    ->sortable(),
                
                Switcher::make('Активна', 'is_active')
                    ->default(true),
            ]),
            
            HasMany::make('Услуги', 'services', resource: new ServiceResource())
                ->creatable()
                ->searchable(),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:service_categories,slug,' . ($item?->id ?: 'null')],
            'sort_order' => ['integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    public function filters(): array
    {
        return [
            Switcher::make('Активна', 'is_active'),
        ];
    }

    public function search(): array
    {
        return ['name', 'slug'];
    }

    public function order(): array
    {
        return ['sort_order' => 'asc'];
    }
}
```

### app/MoonShine/Resources/ServiceResource.php

```php
<?php

namespace App\MoonShine\Resources;

use App\Models\Service;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use MoonShine\UI\Fields\TinyMce;

class ServiceResource extends ModelResource
{
    protected string $model = Service::class;
    
    protected string $title = 'Услуги';

    protected array $with = ['category'];

    public function fields(): array
    {
        return [
            Grid::make([
                Column::make([
                    Box::make('Основная информация', [
                        ID::make()->sortable(),
                        
                        BelongsTo::make('Категория', 'category', resource: new ServiceCategoryResource())
                            ->required()
                            ->searchable(),
                        
                        Text::make('Название', 'name')
                            ->required(),
                        
                        Text::make('Slug', 'slug')
                            ->required()
                            ->hint('URL-идентификатор услуги'),
                        
                        Textarea::make('Краткое описание', 'short_description')
                            ->nullable()
                            ->rows(3),
                        
                        TinyMce::make('Полное описание', 'full_description')
                            ->nullable(),
                        
                        Image::make('Изображение', 'image')
                            ->dir('services')
                            ->allowedExtensions(['jpg', 'png', 'webp'])
                            ->removable(),
                        
                        Json::make('Галерея', 'gallery')
                            ->nullable()
                            ->fields([
                                Image::make('Изображение', 'image')
                                    ->dir('services/gallery'),
                                Text::make('Подпись', 'caption'),
                            ])
                            ->creatable()
                            ->removable(),
                    ]),
                ])->columnSpan(8),
                
                Column::make([
                    Box::make('Цены и параметры', [
                        Number::make('Цена от', 'price_from')
                            ->nullable()
                            ->step(0.01)
                            ->min(0),
                        
                        Number::make('Цена до', 'price_to')
                            ->nullable()
                            ->step(0.01)
                            ->min(0),
                        
                        Number::make('Площадь от (м²)', 'area_from')
                            ->nullable()
                            ->min(0),
                        
                        Number::make('Площадь до (м²)', 'area_to')
                            ->nullable()
                            ->min(0),
                        
                        Text::make('Срок выполнения', 'duration')
                            ->nullable()
                            ->placeholder('например: 2-3 месяца'),
                        
                        Json::make('Особенности', 'features')
                            ->keyValue()
                            ->creatable()
                            ->removable(),
                        
                        Switcher::make('Активна', 'is_active')
                            ->default(true),
                    ]),
                    
                    Box::make('SEO', [
                        Text::make('Meta Title', 'meta_title')
                            ->nullable(),
                        
                        Textarea::make('Meta Description', 'meta_description')
                            ->nullable()
                            ->rows(3),
                    ]),
                ])->columnSpan(4),
            ])->columnSpan(12),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'category_id' => ['required', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:services,slug,' . ($item?->id ?: 'null')],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'price_to' => ['nullable', 'numeric', 'min:0', 'gte:price_from'],
            'area_from' => ['nullable', 'integer', 'min:0'],
            'area_to' => ['nullable', 'integer', 'min:0', 'gte:area_from'],
            'is_active' => ['boolean'],
        ];
    }

    public function filters(): array
    {
        return [
            BelongsTo::make('Категория', 'category', resource: new ServiceCategoryResource())
                ->searchable(),
            Switcher::make('Активна', 'is_active'),
        ];
    }

    public function search(): array
    {
        return ['name', 'slug', 'short_description'];
    }
}
```

### app/MoonShine/Resources/PortfolioResource.php

```php
<?php

namespace App\MoonShine\Resources;

use App\Models\Portfolio;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use MoonShine\UI\Fields\TinyMce;

class PortfolioResource extends ModelResource
{
    protected string $model = Portfolio::class;
    
    protected string $title = 'Портфолио';

    protected array $with = ['service', 'images'];

    public function fields(): array
    {
        return [
            Grid::make([
                Column::make([
                    Box::make('Основная информация', [
                        ID::make()->sortable(),
                        
                        Text::make('Название проекта', 'title')
                            ->required(),
                        
                        Text::make('Slug', 'slug')
                            ->required(),
                        
                        BelongsTo::make('Услуга', 'service', resource: new ServiceResource())
                            ->nullable()
                            ->searchable(),
                        
                        TinyMce::make('Описание', 'description')
                            ->nullable(),
                        
                        Text::make('Имя клиента', 'client_name')
                            ->nullable(),
                        
                        Date::make('Дата завершения', 'completion_date')
                            ->nullable()
                            ->format('d.m.Y'),
                        
                        Number::make('Площадь (м²)', 'area')
                            ->nullable()
                            ->min(0),
                        
                        Text::make('Адрес/локация', 'location')
                            ->nullable(),
                    ]),
                    
                    Box::make('Изображения', [
                        Image::make('Обложка', 'thumbnail')
                            ->dir('portfolio')
                            ->allowedExtensions(['jpg', 'png', 'webp'])
                            ->removable(),
                        
                        Image::make('Фото "до"', 'before_image')
                            ->dir('portfolio/before')
                            ->removable(),
                        
                        Image::make('Фото "после"', 'after_image')
                            ->dir('portfolio/after')
                            ->removable(),
                    ]),
                ])->columnSpan(8),
                
                Column::make([
                    Box::make('Настройки', [
                        Switcher::make('На главной', 'is_featured')
                            ->default(false),
                        
                        Switcher::make('Активен', 'is_active')
                            ->default(true),
                    ]),
                    
                    Box::make('SEO', [
                        Text::make('Meta Title', 'meta_title')
                            ->nullable(),
                        
                        Textarea::make('Meta Description', 'meta_description')
                            ->nullable()
                            ->rows(3),
                    ]),
                ])->columnSpan(4),
            ])->columnSpan(12),
            
            HasMany::make('Галерея', 'images', resource: new PortfolioImageResource())
                ->creatable()
                ->modifyTable(fn ($table) => $table->sortable('sort_order')),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:portfolios,slug,' . ($item?->id ?: 'null')],
            'service_id' => ['nullable', 'exists:services,id'],
            'area' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ];
    }

    public function filters(): array
    {
        return [
            BelongsTo::make('Услуга', 'service', resource: new ServiceResource())
                ->searchable(),
            Switcher::make('На главной', 'is_featured'),
            Switcher::make('Активен', 'is_active'),
        ];
    }

    public function search(): array
    {
        return ['title', 'slug', 'location', 'client_name'];
    }
}
```

### app/MoonShine/Resources/PortfolioImageResource.php

```php
<?php

namespace App\MoonShine\Resources;

use App\Models\PortfolioImage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

class PortfolioImageResource extends ModelResource
{
    protected string $model = PortfolioImage::class;
    
    protected string $title = 'Изображения';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            
            Image::make('Изображение', 'image')
                ->dir('portfolio/gallery')
                ->required()
                ->removable(),
            
            Text::make('Подпись', 'caption')
                ->nullable(),
            
            Number::make('Порядок', 'sort_order')
                ->default(0)
                ->buttons(),
        ];
    }
}
```

### app/MoonShine/Resources/ReviewResource.php

```php
<?php

namespace App\MoonShine\Resources;

use App\Models\Review;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class ReviewResource extends ModelResource
{
    protected string $model = Review::class;
    
    protected string $title = 'Отзывы';

    protected array $with = ['service', 'portfolio'];

    protected bool $createInModal = true;

    public function fields(): array
    {
        return [
            Box::make('Информация об авторе', [
                ID::make()->sortable(),
                
                Text::make('Имя', 'author_name')
                    ->required(),
                
                Text::make('Телефон', 'author_phone')
                    ->nullable(),
                
                Text::make('Email', 'author_email')
                    ->nullable(),
            ]),
            
            Box::make('Содержание отзыва', [
                Select::make('Рейтинг', 'rating')
                    ->options([
                        1 => '⭐ (1)',
                        2 => '⭐⭐ (2)',
                        3 => '⭐⭐⭐ (3)',
                        4 => '⭐⭐⭐⭐ (4)',
                        5 => '⭐⭐⭐⭐⭐ (5)',
                    ])
                    ->default(5)
                    ->required(),
                
                Textarea::make('Текст отзыва', 'text')
                    ->required()
                    ->rows(5),
                
                Textarea::make('Ответ администрации', 'admin_reply')
                    ->nullable()
                    ->rows(3),
            ]),
            
            Box::make('Связи', [
                BelongsTo::make('Услуга', 'service', resource: new ServiceResource())
                    ->nullable()
                    ->searchable(),
                
                BelongsTo::make('Проект', 'portfolio', resource: new PortfolioResource())
                    ->nullable()
                    ->searchable(),
            ]),
            
            Box::make('Модерация', [
                Switcher::make('Опубликован', 'is_published')
                    ->default(false),
                
                Switcher::make('Проверен', 'is_verified')
                    ->default(false),
            ]),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'author_name' => ['required', 'string', 'max:255'],
            'author_phone' => ['nullable', 'string', 'max:50'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'text' => ['required', 'string'],
            'is_published' => ['boolean'],
            'is_verified' => ['boolean'],
        ];
    }

    public function filters(): array
    {
        return [
            Select::make('Рейтинг', 'rating')
                ->options([1, 2, 3, 4, 5]),
            Switcher::make('Опубликован', 'is_published'),
            Switcher::make('Проверен', 'is_verified'),
        ];
    }

    public function search(): array
    {
        return ['author_name', 'text'];
    }

    public function order(): array
    {
        return ['created_at' => 'desc'];
    }

    public function bulkActions(): array
    {
        return [
            'publish' => 'Опубликовать',
            'unpublish' => 'Снять с публикации',
            'verify' => 'Пометить проверенными',
        ];
    }
}
```

### app/MoonShine/Resources/LeadResource.php

```php
<?php

namespace App\MoonShine\Resources;

use App\Models\Lead;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Actions\Action;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class LeadResource extends ModelResource
{
    protected string $model = Lead::class;
    
    protected string $title = 'Заявки';

    protected array $with = ['service', 'processor'];

    public function fields(): array
    {
        return [
            Box::make('Контактная информация', [
                ID::make()->sortable(),
                
                Text::make('Имя', 'name')
                    ->required(),
                
                Text::make('Телефон', 'phone')
                    ->required(),
                
                Text::make('Email', 'email')
                    ->nullable(),
                
                Textarea::make('Сообщение', 'message')
                    ->nullable()
                    ->rows(3),
            ]),
            
            Box::make('Детали заявки', [
                BelongsTo::make('Услуга', 'service', resource: new ServiceResource())
                    ->nullable()
                    ->searchable(),
                
                Number::make('Рассчитанная цена', 'calculated_price')
                    ->nullable()
                    ->step(0.01),
                
                Number::make('Рассчитанная площадь', 'calculated_area')
                    ->nullable(),
                
                Select::make('Источник', 'source')
                    ->options([
                        'form' => 'Форма на сайте',
                        'calculator' => 'Калькулятор',
                        'direct' => 'Прямой звонок',
                        'phone' => 'Обратный звонок',
                    ])
                    ->default('form'),
                
                Select::make('Статус', 'status')
                    ->options([
                        'new' => 'Новая',
                        'processing' => 'В обработке',
                        'completed' => 'Завершена',
                        'cancelled' => 'Отменена',
                    ])
                    ->badge(fn($status) => match($status) {
                        'new' => 'red',
                        'processing' => 'yellow',
                        'completed' => 'green',
                        'cancelled' => 'gray',
                    })
                    ->default('new'),
            ]),
            
            Box::make('UTM метки', [
                Text::make('UTM Source', 'utm_source')->nullable(),
                Text::make('UTM Medium', 'utm_medium')->nullable(),
                Text::make('UTM Campaign', 'utm_campaign')->nullable(),
            ])->collapsed(),
            
            Box::make('Системная информация', [
                Date::make('Дата создания', 'created_at')
                    ->format('d.m.Y H:i')
                    ->sortable(),
                
                Text::make('IP адрес', 'ip_address')->nullable(),
                
                BelongsTo::make('Обработал', 'processor', resource: new \MoonShine\Laravel\Resources\MoonShineUserResource())
                    ->nullable(),
            ])->collapsed(),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', 'in:new,processing,completed,cancelled'],
        ];
    }

    public function filters(): array
    {
        return [
            Select::make('Статус', 'status')
                ->options([
                    'new' => 'Новая',
                    'processing' => 'В обработке',
                    'completed' => 'Завершена',
                    'cancelled' => 'Отменена',
                ]),
            BelongsTo::make('Услуга', 'service', resource: new ServiceResource())
                ->searchable(),
            Date::make('Дата с', 'created_at')
                ->format('d.m.Y'),
            Date::make('Дата по', 'created_at')
                ->format('d.m.Y'),
        ];
    }

    public function search(): array
    {
        return ['name', 'phone', 'email', 'message'];
    }

    public function order(): array
    {
        return ['created_at' => 'desc'];
    }

    public function actions(): array
    {
        return [
            Action::make('Экспорт CSV', 'export_csv')
                ->method('exportCsv')
                ->icon('heroicons.document-arrow-down'),
            
            Action::make('Экспорт Excel', 'export_excel')
                ->method('exportExcel')
                ->icon('heroicons.document-spreadsheet'),
            
            Action::make('Пометить обработанными', 'mark_processed')
                ->method('markProcessed')
                ->icon('heroicons.check-circle'),
        ];
    }
}
```

### app/MoonShine/Resources/TeamMemberResource.php

```php
<?php

namespace App\MoonShine\Resources;

use App\Models\TeamMember;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class TeamMemberResource extends ModelResource
{
    protected string $model = TeamMember::class;
    
    protected string $title = 'Команда';

    public function fields(): array
    {
        return [
            Box::make('Информация о сотруднике', [
                ID::make()->sortable(),
                
                Text::make('ФИО', 'full_name')
                    ->required(),
                
                Text::make('Должность', 'position')
                    ->required(),
                
                Image::make('Фото', 'photo')
                    ->dir('team')
                    ->allowedExtensions(['jpg', 'png', 'webp'])
                    ->removable(),
                
                Textarea::make('Биография', 'bio')
                    ->nullable()
                    ->rows(4),
                
                Text::make('Телефон', 'phone')
                    ->nullable(),
                
                Text::make('Email', 'email')
                    ->nullable(),
                
                Number::make('Порядок', 'sort_order')
                    ->default(0)
                    ->buttons(),
                
                Switcher::make('Активен', 'is_active')
                    ->default(true),
            ]),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }

    public function order(): array
    {
        return ['sort_order' => 'asc'];
    }
}
```

### app/MoonShine/Resources/SettingResource.php

```php
<?php

namespace App\MoonShine\Resources;

use App\Models\Setting;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class SettingResource extends ModelResource
{
    protected string $model = Setting::class;
    
    protected string $title = 'Настройки';

    public function fields(): array
    {
        return [
            Box::make('Настройка', [
                ID::make()->sortable(),
                
                Text::make('Ключ', 'key')
                    ->required()
                    ->hint('Используется в коде для получения значения'),
                
                Text::make('Название', 'label')
                    ->required(),
                
                Select::make('Тип', 'type')
                    ->options([
                        'string' => 'Строка',
                        'text' => 'Текст',
                        'number' => 'Число',
                        'boolean' => 'Да/Нет',
                        'file' => 'Файл',
                        'json' => 'JSON',
                    ])
                    ->default('string')
                    ->required(),
                
                Text::make('Группа', 'group')
                    ->default('general')
                    ->hint('контакты, соцсети, баннер и т.д.'),
                
                Number::make('Порядок', 'sort_order')
                    ->default(0)
                    ->buttons(),
            ]),
            
            Box::make('Значение', [
                Text::make('Значение (строка)', 'value')
                    ->when(fn($field) => $field->getFormValue()?->type === 'string'),
                
                Textarea::make('Значение (текст)', 'value')
                    ->when(fn($field) => $field->getFormValue()?->type === 'text'),
                
                Number::make('Значение (число)', 'value')
                    ->when(fn($field) => $field->getFormValue()?->type === 'number'),
                
                Switcher::make('Значение (да/нет)', 'value')
                    ->when(fn($field) => $field->getFormValue()?->type === 'boolean'),
                
                Image::make('Значение (файл)', 'value')
                    ->dir('settings')
                    ->when(fn($field) => $field->getFormValue()?->type === 'file'),
                
                Textarea::make('Значение (JSON)', 'value')
                    ->when(fn($field) => $field->getFormValue()?->type === 'json'),
            ]),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'key' => ['required', 'string', 'max:255', 'unique:settings,key,' . ($item?->id ?: 'null')],
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:string,number,boolean,json,file,text'],
            'group' => ['required', 'string', 'max:100'],
        ];
    }

    public function filters(): array
    {
        return [
            Text::make('Группа', 'group'),
        ];
    }

    public function order(): array
    {
        return ['group' => 'asc', 'sort_order' => 'asc'];
    }
}
```

## 2.2 Кастомные действия

### app/MoonShine/Controllers/LeadExportController.php

```php
<?php

namespace App\MoonShine\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadExportController
{
    public function exportCsv(Request $request)
    {
        $leads = Lead::with('service')
            ->when($request->has('ids'), fn($q) => $q->whereIn('id', $request->ids))
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="leads_' . now()->format('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use ($leads) {
            $handle = fopen('php://output', 'w');
            
            // BOM для Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Заголовки
            fputcsv($handle, ['ID', 'Имя', 'Телефон', 'Email', 'Услуга', 'Статус', 'Дата']);
            
            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->id,
                    $lead->name,
                    $lead->phone,
                    $lead->email,
                    $lead->service?->name,
                    match($lead->status) {
                        'new' => 'Новая',
                        'processing' => 'В обработке',
                        'completed' => 'Завершена',
                        'cancelled' => 'Отменена',
                    },
                    $lead->created_at->format('d.m.Y H:i'),
                ]);
            }
            
            fclose($handle);
        }, 200, $headers);
    }

    public function exportExcel(Request $request)
    {
        // Реализация через maatwebsite/excel
        return (new \App\Exports\LeadsExport($request->ids ?? []))
            ->download('leads_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function markProcessed(Request $request)
    {
        Lead::whereIn('id', $request->ids ?? [])
            ->update([
                'status' => 'processing',
                'processed_at' => now(),
                'processed_by' => auth('moonshine')->id(),
            ]);

        return back()->with('success', 'Заявки помечены как обрабатываемые');
    }
}
```

## 2.3 Регистрация ресурсов

### app/Providers/MoonShineServiceProvider.php

```php
<?php

namespace App\Providers;

use App\MoonShine\Resources\LeadResource;
use App\MoonShine\Resources\PageResource;
use App\MoonShine\Resources\PortfolioResource;
use App\MoonShine\Resources\ReviewResource;
use App\MoonShine\Resources\ServiceCategoryResource;
use App\MoonShine\Resources\ServiceResource;
use App\MoonShine\Resources\SettingResource;
use App\MoonShine\Resources\TeamMemberResource;
use MoonShine\Laravel\Providers\MoonShineApplicationServiceProvider;
use MoonShine\Laravel\Resources\MoonShineUserResource;
use MoonShine\MenuManager\MenuItem;
use MoonShine\UI\MoonShineLayout;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function resources(): array
    {
        return [
            new ServiceCategoryResource(),
            new ServiceResource(),
            new PortfolioResource(),
            new ReviewResource(),
            new LeadResource(),
            new TeamMemberResource(),
            new SettingResource(),
            new PageResource(),
        ];
    }

    protected function menu(): array
    {
        return [
            MenuItem::make('Услуги', new ServiceResource(), 'heroicons.building-storefront'),
            MenuItem::make('Категории', new ServiceCategoryResource(), 'heroicons.folder'),
            MenuItem::make('Портфолио', new PortfolioResource(), 'heroicons.photo'),
            MenuItem::make('Отзывы', new ReviewResource(), 'heroicons.star'),
            MenuItem::make('Заявки', new LeadResource(), 'heroicons.inbox'),
            MenuItem::make('Команда', new TeamMemberResource(), 'heroicons.users'),
            MenuItem::make('Страницы', new PageResource(), 'heroicons.document'),
            MenuItem::make('Настройки', new SettingResource(), 'heroicons.cog-6-tooth'),
            MenuItem::make('Пользователи', new MoonShineUserResource(), 'heroicons.user-group'),
        ];
    }

    protected function theme(): array
    {
        return [
            'colors' => [
                'primary' => '#f59e0b',
                'secondary' => '#64748b',
            ],
            'logo' => '/images/logo-admin.png',
            'logo_small' => '/images/logo-admin-small.png',
        ];
    }
}
```

## Настройки сайта (примеры)

### Начальные настройки (Seeder)

```php
// database/seeders/SettingSeeder.php
$settings = [
    // Контакты
    ['key' => 'company_name', 'label' => 'Название компании', 'group' => 'contacts', 'type' => 'string', 'value' => 'ООО «Золотой Тур»'],
    ['key' => 'company_phone', 'label' => 'Телефон', 'group' => 'contacts', 'type' => 'string', 'value' => '+7 (XXX) XXX-XX-XX'],
    ['key' => 'company_email', 'label' => 'Email', 'group' => 'contacts', 'type' => 'string', 'value' => 'info@goldentour.ru'],
    ['key' => 'company_address', 'label' => 'Адрес', 'group' => 'contacts', 'type' => 'text', 'value' => 'г. Минск, ул. Примерная, 123'],
    ['key' => 'company_work_hours', 'label' => 'Часы работы', 'group' => 'contacts', 'type' => 'string', 'value' => 'Пн-Пт: 9:00-18:00'],
    
    // Соцсети
    ['key' => 'social_vk', 'label' => 'ВКонтакте', 'group' => 'social', 'type' => 'string', 'value' => ''],
    ['key' => 'social_telegram', 'label' => 'Telegram', 'group' => 'social', 'type' => 'string', 'value' => ''],
    ['key' => 'social_whatsapp', 'label' => 'WhatsApp', 'group' => 'social', 'type' => 'string', 'value' => ''],
    
    // Главный баннер
    ['key' => 'hero_title', 'label' => 'Заголовок баннера', 'group' => 'hero', 'type' => 'string', 'value' => 'Строительство и ремонт под ключ'],
    ['key' => 'hero_subtitle', 'label' => 'Подзаголовок', 'group' => 'hero', 'type' => 'text', 'value' => 'Качественно, в срок и по честной цене'],
    ['key' => 'hero_image', 'label' => 'Фон баннера', 'group' => 'hero', 'type' => 'file', 'value' => ''],
    ['key' => 'hero_button_text', 'label' => 'Текст кнопки', 'group' => 'hero', 'type' => 'string', 'value' => 'Получить консультацию'],
    
    // SEO
    ['key' => 'site_title', 'label' => 'Заголовок сайта', 'group' => 'seo', 'type' => 'string', 'value' => 'Золотой Тур — Строительная компания'],
    ['key' => 'site_description', 'label' => 'Описание сайта', 'group' => 'seo', 'type' => 'text', 'value' => 'Профессиональное строительство и ремонт'],
];
```
