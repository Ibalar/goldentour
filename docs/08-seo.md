# 08. Этап 5 — SEO

## 5.1 ЧПУ (Slug)

### Автоматическая генерация slug

```php
<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->name ?? $model->title);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name') || $model->isDirty('title')) {
                $model->slug = $model->generateUniqueSlug(
                    $model->name ?? $model->title,
                    $model->id
                );
            }
        });
    }

    public function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    protected function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = static::where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
```

### Использование в моделях

```php
<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasSlug;
    
    // ...
}
```

### Примеры URL

| Страница | URL |
|----------|-----|
| Главная | `/` |
| Список услуг | `/services` |
| Категория | `/services/category/remont` |
| Услуга | `/services/remont-kvartir` |
| Портфолио | `/portfolio` |
| Проект | `/portfolio/dom-v-minske` |
| Калькулятор | `/calculator` |
| О компании | `/about` |
| Контакты | `/contacts` |

## 5.2 Мета-теги

### Миграция для meta-полей

```php
<?php
// Добавить в существующие миграции или создать новую

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('is_active');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
        });

        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('is_active');
            $table->text('meta_description')->nullable()->after('meta_title');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('is_active');
            $table->text('meta_description')->nullable()->after('meta_title');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords']);
        });

        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }
};
```

### Компонент meta-тегов

```php
<?php

// app/View/Components/SeoMeta.php

namespace App\View\Components;

use App\Models\Setting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SeoMeta extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $keywords = null,
        public ?string $image = null,
        public ?string $type = 'website',
    ) {}

    public function render(): View|Closure|string
    {
        $defaultTitle = Setting::get('site_title', 'Золотой Тур — Строительная компания');
        $defaultDescription = Setting::get('site_description', '');
        $defaultImage = asset('images/og-image.jpg');

        return view('components.seo-meta', [
            'title' => $this->title ? $this->title . ' | Золотой Тур' : $defaultTitle,
            'description' => $this->description ?? $defaultDescription,
            'keywords' => $this->keywords,
            'image' => $this->image ? asset('storage/' . $this->image) : $defaultImage,
            'type' => $this->type,
            'url' => url()->current(),
        ]);
    }
}
```

### resources/views/components/seo-meta.blade.php

```html
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
@if($keywords)
    <meta name="keywords" content="{{ $keywords }}">
@endif

<!-- Open Graph -->
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:site_name" content="Золотой Тур">
<meta property="og:locale" content="ru_RU">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

<!-- Canonical -->
<link rel="canonical" href="{{ $url }}">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
```

### Использование в шаблонах

```html
<!-- В layouts/app.blade.php -->
<head>
    <x-seo-meta 
        :title="$meta_title ?? null"
        :description="$meta_description ?? null"
        :image="$meta_image ?? null"
    />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<!-- В контроллере -->
public function show(Service $service)
{
    return view('pages.services.show', [
        'service' => $service,
        'meta_title' => $service->meta_title ?? $service->name,
        'meta_description' => $service->meta_description ?? $service->short_description,
        'meta_image' => $service->image,
    ]);
}
```

## 5.3 Sitemap.xml

### resources/views/sitemap.blade.php

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static pages -->
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    
    <url>
        <loc>{{ url('/services') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    
    <url>
        <loc>{{ url('/portfolio') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    
    <url>
        <loc>{{ url('/about') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    
    <url>
        <loc>{{ url('/contacts') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    
    <url>
        <loc>{{ url('/calculator') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <!-- Services -->
    @foreach(\App\Models\Service::active()->get() as $service)
        <url>
            <loc>{{ route('services.show', $service->slug) }}</loc>
            <lastmod>{{ $service->updated_at->toDateString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
    
    <!-- Service Categories -->
    @foreach(\App\Models\ServiceCategory::where('is_active', true)->get() as $category)
        <url>
            <loc>{{ route('services.category', $category->slug) }}</loc>
            <lastmod>{{ $category->updated_at->toDateString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
    
    <!-- Portfolio -->
    @foreach(\App\Models\Portfolio::active()->get() as $item)
        <url>
            <loc>{{ route('portfolio.show', $item->slug) }}</loc>
            <lastmod>{{ $item->updated_at->toDateString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
    
    <!-- Pages -->
    @foreach(\App\Models\Page::where('is_active', true)->get() as $page)
        <url>
            <loc>{{ route('page', $page->slug) }}</loc>
            <lastmod>{{ $page->updated_at->toDateString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>
```

### Консольная команда для генерации

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml file';

    public function handle(): void
    {
        $sitemap = view('sitemap')->render();
        
        Storage::disk('public')->put('sitemap.xml', $sitemap);
        
        // Также сохраняем в public для доступности
        file_put_contents(public_path('sitemap.xml'), $sitemap);
        
        $this->info('Sitemap generated successfully!');
    }
}
```

### Регистрация команды

```php
// routes/console.php
use Illuminate\Support\Facades\Schedule;

Schedule::command('sitemap:generate')->daily();
```

## 5.4 Robots.txt

### Динамический robots.txt

```php
// routes/web.php

Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Allow: /',
        '',
        '# Sitemap',
        'Sitemap: ' . url('/sitemap.xml'),
        '',
        '# Disallow',
        'Disallow: /admin',
        'Disallow: /storage',
        'Disallow: /*?*utm_',
        'Disallow: /*?*fbclid',
    ];
    
    if (app()->environment('local', 'staging')) {
        $lines = [
            'User-agent: *',
            'Disallow: /',
        ];
    }
    
    return response(implode("\n", $lines), 200, [
        'Content-Type' => 'text/plain',
    ]);
});
```

### Статический robots.txt (public/robots.txt)

```
User-agent: *
Allow: /

# Sitemap
Sitemap: https://goldentour.ru/sitemap.xml

# Disallow admin paths
Disallow: /admin
Disallow: /admin/*

# Disallow technical paths
Disallow: /storage
Disallow: /vendor
Disallow: /node_modules

# Disallow URL parameters
Disallow: /*?*utm_source=
Disallow: /*?*utm_medium=
Disallow: /*?*utm_campaign=
Disallow: /*?*fbclid=

# Crawl-delay
Crawl-delay: 10
```

## 5.5 Микроразметка Schema.org

### Organization schema

```html
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ConstructionBusiness",
    "name": "{{ \App\Models\Setting::get('company_name', 'Золотой Тур') }}",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/logo.png') }}",
    "telephone": "{{ \App\Models\Setting::get('company_phone') }}",
    "email": "{{ \App\Models\Setting::get('company_email') }}",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "{{ \App\Models\Setting::get('company_address') }}",
        "addressLocality": "Минск",
        "addressCountry": "BY"
    },
    "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
        "opens": "09:00",
        "closes": "18:00"
    },
    "priceRange": "$$",
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "reviewCount": "{{ \App\Models\Review::published()->count() }}"
    }
}
</script>
```

### Service schema

```html
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "name": "{{ $service->name }}",
    "description": "{{ $service->short_description }}",
    "provider": {
        "@type": "ConstructionBusiness",
        "name": "Золотой Тур"
    },
    "offers": {
        "@type": "Offer",
        "price": "{{ $service->price_from }}",
        "priceCurrency": "RUB"
    },
    "areaServed": {
        "@type": "City",
        "name": "Минск"
    }
}
</script>
```

### BreadcrumbList schema

```html
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Главная",
            "item": "{{ url('/') }}"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Услуги",
            "item": "{{ route('services.index') }}"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "{{ $service->name }}"
        }
    ]
}
</script>
```

## 5.6 Дополнительные SEO настройки

### app/Http/Middleware/SeoMiddleware.php

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SeoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Добавляем заголовки для SEO
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Кэширование статических ресурсов
        if ($request->is('*.css', '*.js', '*.jpg', '*.png', '*.webp', '*.woff2')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        }
        
        return $response;
    }
}
```

### Редиректы с www и без / на конце

```nginx
# /etc/nginx/sites-available/goldentour

# Редирект с www на без www
server {
    listen 80;
    server_name www.goldentour.ru;
    return 301 $scheme://goldentour.ru$request_uri;
}

# Редирект с / на конце
rewrite ^/(.*)/$ /$1 permanent;
```

### Генерация социальных изображений

```php
<?php

namespace App\Services;

use Intervention\Image\Facades\Image;

class OpenGraphImageGenerator
{
    public function generate(string $title, ?string $image = null): string
    {
        $canvas = Image::canvas(1200, 630, '#f59e0b');
        
        // Фоновое изображение
        if ($image && file_exists(storage_path('app/public/' . $image))) {
            $bg = Image::make(storage_path('app/public/' . $image))->fit(1200, 630);
            $canvas->insert($bg);
            $canvas->blur(5);
            $canvas->fill('rgba(0, 0, 0, 0.5)');
        }
        
        // Логотип
        $logo = Image::make(public_path('images/logo-white.png'))->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $canvas->insert($logo, 'top-left', 50, 50);
        
        // Заголовок
        $canvas->text($title, 600, 315, function ($font) {
            $font->file(public_path('fonts/Inter-Bold.ttf'));
            $font->size(60);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
            $font->wrap(1000);
        });
        
        $filename = 'og/' . md5($title) . '.jpg';
        $canvas->save(storage_path('app/public/' . $filename), 80);
        
        return $filename;
    }
}
```
