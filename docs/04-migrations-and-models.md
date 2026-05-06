# 04. Этап 1 — Миграции и модели

## 1.1 Миграции

### Создание миграций

```bash
# Категории услуг
php artisan make:migration create_service_categories_table

# Услуги
php artisan make:migration create_services_table

# Портфолио
php artisan make:migration create_portfolios_table
php artisan make:migration create_portfolio_images_table

# Отзывы
php artisan make:migration create_reviews_table

# Заявки
php artisan make:migration create_leads_table

# Настройки
php artisan make:migration create_settings_table

# Страницы
php artisan make:migration create_pages_table

# Команда
php artisan make:migration create_team_members_table
```

### database/migrations/xxxx_xx_xx_000001_create_service_categories_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_categories');
    }
};
```

### database/migrations/xxxx_xx_xx_000002_create_services_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('service_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->decimal('price_from', 12, 2)->nullable();
            $table->decimal('price_to', 12, 2)->nullable();
            $table->integer('area_from')->nullable()->comment('Площадь от, м²');
            $table->integer('area_to')->nullable()->comment('Площадь до, м²');
            $table->string('duration')->nullable()->comment('Срок выполнения');
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
```

### database/migrations/xxxx_xx_xx_000003_create_portfolios_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('client_name')->nullable();
            $table->date('completion_date')->nullable();
            $table->integer('area')->nullable()->comment('Площадь, м²');
            $table->string('location')->nullable();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('thumbnail')->nullable();
            $table->string('before_image')->nullable();
            $table->string('after_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
```

### database/migrations/xxxx_xx_xx_000004_create_portfolio_images_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained('portfolios')->onDelete('cascade');
            $table->string('image');
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_images');
    }
};
```

### database/migrations/xxxx_xx_xx_000005_create_reviews_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('author_phone')->nullable();
            $table->string('author_email')->nullable();
            $table->tinyInteger('rating')->unsigned()->default(5);
            $table->text('text');
            $table->boolean('is_published')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->foreignId('portfolio_id')->nullable()->constrained('portfolios')->nullOnDelete();
            $table->text('admin_reply')->nullable();
            $table->timestamps();
            
            $table->index('is_published');
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
```

### database/migrations/xxxx_xx_xx_000006_create_leads_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('message')->nullable();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->decimal('calculated_price', 12, 2)->nullable();
            $table->integer('calculated_area')->nullable();
            $table->enum('source', ['form', 'calculator', 'direct', 'phone'])->default('form');
            $table->enum('status', ['new', 'processing', 'completed', 'cancelled'])->default('new');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('moonshine_users')->nullOnDelete();
            $table->timestamps();
            
            $table->index('status');
            $table->index('source');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
```

### database/migrations/xxxx_xx_xx_000007_create_team_members_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('position');
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
```

### database/migrations/xxxx_xx_xx_000008_create_settings_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->enum('type', ['string', 'number', 'boolean', 'json', 'file', 'text'])->default('string');
            $table->string('group')->default('general');
            $table->string('label');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
```

### database/migrations/xxxx_xx_xx_000009_create_pages_table.php

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('template')->default('default');
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
```

## 1.2 Seeders

### Создание сидеров

```bash
php artisan make:seeder ServiceCategorySeeder
php artisan make:seeder ServiceSeeder
php artisan make:seeder PortfolioSeeder
php artisan make:seeder ReviewSeeder
php artisan make:seeder SettingSeeder
php artisan make:seeder TeamMemberSeeder
```

### database/seeders/ServiceCategorySeeder.php

```php
<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Строительство',
                'slug' => 'stroitelstvo',
                'description' => 'Строительство домов, коттеджей и коммерческих объектов',
                'sort_order' => 1,
            ],
            [
                'name' => 'Ремонт',
                'slug' => 'remont',
                'description' => 'Ремонт квартир, офисов и других помещений',
                'sort_order' => 2,
            ],
            [
                'name' => 'Отделка',
                'slug' => 'otdelka',
                'description' => 'Внутренняя и внешняя отделка помещений',
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::create($category);
        }
    }
}
```

### database/seeders/ServiceSeeder.php

```php
<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ServiceCategory::all();
        
        $services = [
            // Строительство
            [
                'category_slug' => 'stroitelstvo',
                'name' => 'Строительство коттеджей',
                'slug' => 'stroitelstvo-kottedzhey',
                'short_description' => 'Полный цикл строительства коттеджей под ключ',
                'price_from' => 2500000,
                'price_to' => 15000000,
                'area_from' => 100,
                'area_to' => 500,
                'duration' => '6-12 месяцев',
                'features' => ['Индивидуальный проект', 'Гарантия 10 лет', 'Поэтапная оплата'],
            ],
            [
                'category_slug' => 'stroitelstvo',
                'name' => 'Строительство бань',
                'slug' => 'stroitelstvo-ban',
                'short_description' => 'Строительство бань и саун любой сложности',
                'price_from' => 500000,
                'price_to' => 3000000,
                'area_from' => 15,
                'area_to' => 100,
                'duration' => '2-4 месяца',
                'features' => ['Русская баня', 'Финская сауна', 'Хамам'],
            ],
            [
                'category_slug' => 'stroitelstvo',
                'name' => 'Строительство гаражей',
                'slug' => 'stroitelstvo-garazhey',
                'short_description' => 'Капитальные гаражи и навесы для авто',
                'price_from' => 300000,
                'price_to' => 1500000,
                'area_from' => 20,
                'area_to' => 100,
                'duration' => '1-2 месяца',
                'features' => ['Железобетон', 'Кирпич', 'Металлоконструкции'],
            ],
            // Ремонт
            [
                'category_slug' => 'remont',
                'name' => 'Ремонт квартир',
                'slug' => 'remont-kvartir',
                'short_description' => 'Косметический и капитальный ремонт квартир',
                'price_from' => 150000,
                'price_to' => 2000000,
                'area_from' => 20,
                'area_to' => 200,
                'duration' => '1-4 месяца',
                'features' => ['Косметический', 'Капитальный', 'Евроремонт'],
            ],
            [
                'category_slug' => 'remont',
                'name' => 'Ремонт офисов',
                'slug' => 'remont-ofisov',
                'short_description' => 'Ремонт коммерческих помещений и офисов',
                'price_from' => 200000,
                'price_to' => 5000000,
                'area_from' => 30,
                'area_to' => 500,
                'duration' => '1-6 месяцев',
                'features' => ['Open space', 'Кабинетная планировка', 'Смешанная'],
            ],
            [
                'category_slug' => 'remont',
                'name' => 'Ремонт домов',
                'slug' => 'remont-domov',
                'short_description' => 'Комплексный ремонт частных домов',
                'price_from' => 500000,
                'price_to' => 8000000,
                'area_from' => 50,
                'area_to' => 400,
                'duration' => '2-8 месяцев',
                'features' => ['Внутренний', 'Внешний', 'Комплексный'],
            ],
            // Отделка
            [
                'category_slug' => 'otdelka',
                'name' => 'Внутренняя отделка',
                'slug' => 'vnutrennyaya-otdelka',
                'short_description' => 'Отделка стен, потолков и полов',
                'price_from' => 100000,
                'price_to' => 3000000,
                'area_from' => 20,
                'area_to' => 300,
                'duration' => '2-6 недель',
                'features' => ['Штукатурка', 'Покраска', 'Обои', 'Плитка'],
            ],
            [
                'category_slug' => 'otdelka',
                'name' => 'Фасадная отделка',
                'slug' => 'fasadnaya-otdelka',
                'short_description' => 'Отделка фасадов зданий и сооружений',
                'price_from' => 200000,
                'price_to' => 5000000,
                'area_from' => 50,
                'area_to' => 1000,
                'duration' => '2-8 недель',
                'features' => ['Сайдинг', 'Штукатурка', 'Кирпич', 'Камень'],
            ],
            [
                'category_slug' => 'otdelka',
                'name' => 'Отделка бань',
                'slug' => 'otdelka-ban',
                'short_description' => 'Специализированная отделка бань и саун',
                'price_from' => 100000,
                'price_to' => 1500000,
                'area_from' => 10,
                'area_to' => 80,
                'duration' => '2-4 недели',
                'features' => ['Вагонка осина', 'Вагонка липа', 'Кедр'],
            ],
        ];

        foreach ($services as $service) {
            $category = $categories->firstWhere('slug', $service['category_slug']);
            if ($category) {
                unset($service['category_slug']);
                $service['category_id'] = $category->id;
                Service::create($service);
            }
        }
    }
}
```

### database/seeders/ReviewSeeder.php

```php
<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            [
                'author_name' => 'Александр Петров',
                'rating' => 5,
                'text' => 'Отличная компания! Построили коттедж за 8 месяцев, всё сделали качественно и в срок. Особенно понравился подход к деталям.',
                'is_published' => true,
                'is_verified' => true,
            ],
            [
                'author_name' => 'Елена Смирнова',
                'rating' => 5,
                'text' => 'Делали ремонт в трёшке. Работники пунктуальные, аккуратные. Результат превзошёл ожидания!',
                'is_published' => true,
                'is_verified' => true,
            ],
            [
                'author_name' => 'Игорь Васильев',
                'rating' => 4,
                'text' => 'Хороший ремонт, но немного затянули сроки на неделю. Качество работ на высоте.',
                'is_published' => true,
                'is_verified' => true,
            ],
            [
                'author_name' => 'Мария Козлова',
                'rating' => 5,
                'text' => 'Строили баню. Всё сделали под ключ, включая печь. Очень довольны результатом!',
                'is_published' => true,
                'is_verified' => true,
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}
```

### database/seeders/DatabaseSeeder.php

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            PortfolioSeeder::class,
            ReviewSeeder::class,
            SettingSeeder::class,
            TeamMemberSeeder::class,
        ]);
    }
}
```

## 1.3 Модели и связи

### Создание моделей

```bash
php artisan make:model ServiceCategory
php artisan make:model Service
php artisan make:model Portfolio
php artisan make:model PortfolioImage
php artisan make:model Review
php artisan make:model Lead
php artisan make:model TeamMember
php artisan make:model Setting
php artisan make:model Page
```

### app/Models/ServiceCategory.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'category_id');
    }

    public function activeServices(): HasMany
    {
        return $this->services()->where('is_active', true);
    }
}
```

### app/Models/Service.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'full_description',
        'price_from',
        'price_to',
        'area_from',
        'area_to',
        'duration',
        'image',
        'gallery',
        'features',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price_from' => 'decimal:2',
        'price_to' => 'decimal:2',
        'area_from' => 'integer',
        'area_to' => 'integer',
        'features' => 'array',
        'gallery' => 'array',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
```

### app/Models/Portfolio.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'client_name',
        'completion_date',
        'area',
        'location',
        'service_id',
        'thumbnail',
        'before_image',
        'after_image',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'completion_date' => 'date',
        'area' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PortfolioImage::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
```

### app/Models/Review.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name',
        'author_phone',
        'author_email',
        'rating',
        'text',
        'is_published',
        'is_verified',
        'service_id',
        'portfolio_id',
        'admin_reply',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_published' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
```

### app/Models/Lead.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'service_id',
        'calculated_price',
        'calculated_area',
        'source',
        'status',
        'ip_address',
        'user_agent',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'processed_at',
        'processed_by',
    ];

    protected $casts = [
        'calculated_price' => 'decimal:2',
        'calculated_area' => 'integer',
        'processed_at' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(\MoonShine\Models\MoonshineUser::class, 'processed_by');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function isNew(): bool
    {
        return $this->status === 'new';
    }
}
```

### app/Models/Setting.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public static function get(string $key, $default = null)
    {
        $settings = Cache::remember('site_settings', 3600, function () {
            return self::all()->keyBy('key');
        });

        $setting = $settings->get($key);

        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'number' => (float) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site_settings'));
        static::deleted(fn () => Cache::forget('site_settings'));
    }
}
```

## Запуск миграций и сидеров

```bash
# Выполнить миграции
php artisan migrate

# Выполнить миграции с сидерами
php artisan migrate --seed

# Откатить миграции
php artisan migrate:rollback

# Пересоздать всё
php artisan migrate:fresh --seed
```
