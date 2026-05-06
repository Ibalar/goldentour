# 09. Этап 6 — Адаптив и тестирование

## 6.1 Адаптивная вёрстка

### Breakpoints (Tailwind)

```javascript
// tailwind.config.js
module.exports = {
    theme: {
        screens: {
            'xs': '320px',      // Мобильные (маленькие)
            'sm': '480px',      // Мобильные (большие)
            'md': '768px',      // Планшеты
            'lg': '1024px',     // Ноутбуки
            'xl': '1280px',     // Десктопы
            '2xl': '1536px',    // Большие экраны
        },
    },
}
```

### Грид-система

```html
<!-- 1 колонка на мобильных, 2 на планшетах, 3 на десктопах -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
    <!-- items -->
</div>

<!-- Адаптивные отступы -->
<section class="py-12 md:py-16 lg:py-24">
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- content -->
    </div>
</div>

<!-- Типографика -->
<h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold">
    Заголовок адаптируется под экран
</h1>

<!-- Скрытие/показ элементов -->
<div class="hidden md:block">
    <!-- Видно только на десктопе -->
</div>

<div class="md:hidden">
    <!-- Видно только на мобильных -->
</div>
```

### Мобильное меню (Alpine.js)

```html
<header class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="text-xl sm:text-2xl font-bold text-primary-600">
                    Золотой Тур
                </a>
            </div>
            
            <!-- Desktop Navigation (скрыто на мобильных) -->
            <nav class="hidden md:flex items-center space-x-4 lg:space-x-8">
                <a href="/services" class="nav-link">Услуги</a>
                <a href="/portfolio" class="nav-link">Портфолио</a>
                <a href="/about" class="nav-link">О компании</a>
                <a href="/contacts" class="nav-link">Контакты</a>
                <a href="/calculator" class="btn-primary hidden lg:inline-flex">Калькулятор</a>
            </nav>
            
            <!-- Mobile Menu Button -->
            <div class="flex items-center md:hidden">
                <button 
                    @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="p-2 rounded-md text-secondary-600 hover:text-primary-600"
                    aria-label="Меню"
                    aria-expanded="mobileMenuOpen"
                >
                    <!-- Иконка гамбургера -->
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Иконка закрытия -->
                    <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu Panel -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden bg-white border-t shadow-lg"
        style="display: none;"
    >
        <div class="px-4 pt-2 pb-4 space-y-1">
            <a href="/services" class="mobile-nav-link">Услуги</a>
            <a href="/portfolio" class="mobile-nav-link">Портфолио</a>
            <a href="/about" class="mobile-nav-link">О компании</a>
            <a href="/contacts" class="mobile-nav-link">Контакты</a>
            <a href="/calculator" class="mobile-nav-link text-primary-600 font-semibold">Калькулятор</a>
        </div>
    </div>
</header>
```

### CSS для навигации

```css
@layer components {
    .nav-link {
        @apply text-secondary-600 hover:text-primary-600 font-medium transition-colors duration-200;
    }
    
    .mobile-nav-link {
        @apply block py-3 px-4 text-base font-medium text-secondary-700 hover:text-primary-600 hover:bg-secondary-50 rounded-lg transition;
    }
    
    .btn-primary {
        @apply inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition shadow-sm;
    }
}
```

### Адаптивные изображения

```html
<!-- Использование srcset для разных размеров -->
<img 
    srcset="
        {{ asset('storage/' . $image) }}?w=400 400w,
        {{ asset('storage/' . $image) }}?w=800 800w,
        {{ asset('storage/' . $image) }}?w=1200 1200w
    "
    sizes="
        (max-width: 640px) 100vw,
        (max-width: 1024px) 50vw,
        33vw
    "
    src="{{ asset('storage/' . $image) }}"
    alt="{{ $title }}"
    class="w-full h-48 sm:h-64 object-cover rounded-xl"
    loading="lazy"
>

<!-- Или через picture -->
<picture>
    <source media="(max-width: 640px)" srcset="{{ $mobileImage }}">
    <source media="(max-width: 1024px)" srcset="{{ $tabletImage }}">
    <img src="{{ $desktopImage }}" alt="{{ $title }}" class="w-full">
</picture>
```

## 6.2 Кроссбраузерность

### Поддерживаемые браузеры

| Браузер | Минимальная версия |
|---------|-------------------|
| Chrome | 90+ |
| Firefox | 88+ |
| Safari | 14+ |
| Edge | 90+ |
| Opera | 76+ |
| iOS Safari | 14+ |
| Chrome Android | 90+ |

### Polyfills и фолбэки

```javascript
// resources/js/polyfills.js

// Intersection Observer
import 'intersection-observer';

// Smooth scroll
if (!('scrollBehavior' in document.documentElement.style)) {
    import('smoothscroll-polyfill').then((module) => {
        module.polyfill();
    });
}

// Resize Observer
if (!window.ResizeObserver) {
    window.ResizeObserver = class ResizeObserver {
        observe() {}
        unobserve() {}
        disconnect() {}
    };
}
```

### CSS фолбэки

```css
/* Flexbox fallback для старых браузеров */
.grid {
    display: flex;
    flex-wrap: wrap;
    margin: -0.5rem;
}

.grid > * {
    flex: 0 0 calc(33.333% - 1rem);
    margin: 0.5rem;
}

@supports (display: grid) {
    .grid {
        display: grid;
        gap: 1rem;
        margin: 0;
    }
    
    .grid > * {
        flex: none;
        margin: 0;
    }
}

/* Container queries fallback */
@supports not (container-type: inline-size) {
    .card {
        width: 100%;
    }
}
```

### Autoprefixer конфигурация

```javascript
// postcss.config.js
module.exports = {
    plugins: {
        tailwindcss: {},
        autoprefixer: {
            overrideBrowserslist: [
                '> 1%',
                'last 2 versions',
                'not dead',
                'not ie 11',
            ],
        },
    },
}
```

## 6.3 Функциональное тестирование

### PHPUnit тесты

```php
<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_services_page_loads(): void
    {
        $response = $this->get('/services');
        
        $response->assertStatus(200);
        $response->assertSee('Наши услуги');
    }

    public function test_service_detail_page_loads(): void
    {
        $category = ServiceCategory::factory()->create();
        $service = Service::factory()->create([
            'category_id' => $category->id,
            'slug' => 'test-service',
        ]);
        
        $response = $this->get('/services/test-service');
        
        $response->assertStatus(200);
        $response->assertSee($service->name);
    }

    public function test_inactive_service_not_visible(): void
    {
        $service = Service::factory()->create([
            'is_active' => false,
            'slug' => 'inactive-service',
        ]);
        
        $response = $this->get('/services/inactive-service');
        
        $response->assertStatus(404);
    }
}
```

```php
<?php

namespace Tests\Feature;

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_form_submission(): void
    {
        $response = $this->post('/leads', [
            'name' => 'Тестовый Пользователь',
            'phone' => '+7 (999) 123-45-67',
            'email' => 'test@example.com',
            'message' => 'Тестовое сообщение',
            'source' => 'form',
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('leads', [
            'name' => 'Тестовый Пользователь',
            'phone' => '+7 (999) 123-45-67',
        ]);
    }

    public function test_lead_form_validation(): void
    {
        $response = $this->post('/leads', [
            'name' => '',
            'phone' => '',
        ]);
        
        $response->assertSessionHasErrors(['name', 'phone']);
    }

    public function test_lead_form_phone_format(): void
    {
        $response = $this->post('/leads', [
            'name' => 'Test',
            'phone' => 'invalid',
        ]);
        
        $response->assertSessionHasErrors('phone');
    }
}
```

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class CalculatorTest extends TestCase
{
    public function test_calculator_page_loads(): void
    {
        $response = $this->get('/calculator');
        
        $response->assertStatus(200);
        $response->assertSee('Калькулятор стоимости');
    }

    public function test_calculator_api(): void
    {
        $service = \App\Models\Service::factory()->create([
            'price_from' => 100000,
            'area_from' => 50,
        ]);
        
        $response = $this->postJson('/calculator/calculate', [
            'service_id' => $service->id,
            'area' => 100,
            'options' => ['materials'],
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'base_price',
            'total_price',
            'options_multiplier',
            'service' => ['name', 'duration'],
        ]);
    }
}
```

### Dusk тесты (браузерные)

```php
<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NavigationTest extends DuskTestCase
{
    public function test_navigation_links(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Золотой Тур')
                ->clickLink('Услуги')
                ->assertPathIs('/services')
                ->assertSee('Наши услуги')
                ->clickLink('Контакты')
                ->assertPathIs('/contacts')
                ->assertSee('Контакты');
        });
    }

    public function test_mobile_menu(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->resize(375, 667)
                ->assertMissing('@desktop-nav')
                ->click('@mobile-menu-button')
                ->waitFor('@mobile-menu')
                ->assertVisible('@mobile-menu')
                ->within('@mobile-menu', function ($menu) {
                    $menu->assertSee('Услуги')
                        ->assertSee('Портфолио')
                        ->assertSee('Контакты');
                });
        });
    }

    public function test_lead_form_submission(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contacts')
                ->type('name', 'Тестовый Пользователь')
                ->type('phone', '+7 (999) 123-45-67')
                ->type('email', 'test@example.com')
                ->type('message', 'Тестовое сообщение из Dusk')
                ->press('Отправить заявку')
                ->waitForText('Ваша заявка принята');
        });
    }

    public function test_calculator_workflow(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/calculator')
                ->select('service', '1')
                ->waitFor('@area-step')
                ->type('@area-input', '100')
                ->click('@next-button')
                ->waitFor('@options-step')
                ->check('@option-materials')
                ->click('@calculate-button')
                ->waitFor('@result')
                ->assertSee('Примерная стоимость');
        });
    }
}
```

### Тестирование загрузки файлов

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_upload(): void
    {
        Storage::fake('public');
        
        $file = UploadedFile::fake()->image('service.jpg', 800, 600);
        
        $response = $this->actingAsAdmin()->postJson('/admin/services', [
            'name' => 'Test Service',
            'slug' => 'test-service',
            'category_id' => 1,
            'image' => $file,
        ]);
        
        $response->assertCreated();
        Storage::disk('public')->assertExists('services/' . $file->hashName());
    }

    public function test_invalid_file_type_rejected(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);
        
        $response = $this->actingAsAdmin()->postJson('/admin/services', [
            'name' => 'Test Service',
            'slug' => 'test-service',
            'category_id' => 1,
            'image' => $file,
        ]);
        
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('image');
    }

    public function test_large_file_rejected(): void
    {
        $file = UploadedFile::fake()->image('large.jpg')->size(10240); // 10MB
        
        $response = $this->actingAsAdmin()->postJson('/admin/services', [
            'name' => 'Test Service',
            'slug' => 'test-service',
            'category_id' => 1,
            'image' => $file,
        ]);
        
        $response->assertUnprocessable();
    }
}
```

### JavaScript тесты (Jest)

```javascript
// tests/js/calculator.test.js
import calculator from '../../resources/js/components/calculator';

describe('Calculator', () => {
    let component;
    
    beforeEach(() => {
        component = calculator();
        component.services = [
            { id: 1, slug: 'remont', name: 'Ремонт', price_from: 100000, area_from: 50 },
        ];
    });

    test('initial state', () => {
        expect(component.step).toBe(1);
        expect(component.serviceId).toBeNull();
        expect(component.area).toBe(50);
    });

    test('select service', () => {
        component.serviceId = 'remont';
        component.onServiceChange();
        
        expect(component.service.name).toBe('Ремонт');
    });

    test('calculate base price', () => {
        component.serviceId = 'remont';
        component.onServiceChange();
        component.area = 100;
        
        expect(component.basePrice).toBe(200000);
    });

    test('format price', () => {
        expect(component.formatPrice(150000)).toBe('150 000');
        expect(component.formatPrice(2500000)).toBe('2 500 000');
    });

    test('progress calculation', () => {
        expect(component.progress).toBe(25);
        
        component.step = 2;
        expect(component.progress).toBe(50);
        
        component.step = 4;
        expect(component.progress).toBe(100);
    });
});
```

### Lighthouse CI

```yaml
# .github/workflows/lighthouse.yml
name: Lighthouse CI

on: [push]

jobs:
  lighthouse:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup Node
        uses: actions/setup-node@v4
        with:
          node-version: 20
          
      - name: Install dependencies
        run: npm ci
        
      - name: Build
        run: npm run build
        
      - name: Run Lighthouse CI
        run: |
          npm install -g @lhci/cli@0.13.x
          lhci autorun
        env:
          LHCI_GITHUB_APP_TOKEN: ${{ secrets.LHCI_GITHUB_APP_TOKEN }}
```

```javascript
// lighthouserc.js
module.exports = {
    ci: {
        collect: {
            url: ['http://localhost:8000/'],
            startServerCommand: 'php artisan serve --port=8000',
        },
        assert: {
            assertions: {
                'categories:performance': ['warn', { minScore: 0.8 }],
                'categories:accessibility': ['error', { minScore: 0.9 }],
                'categories:best-practices': ['warn', { minScore: 0.9 }],
                'categories:seo': ['error', { minScore: 0.9 }],
            },
        },
    },
};
```

## 6.4 Чек-лист тестирования

### Функциональность

- [ ] Главная страница загружается
- [ ] Навигация работает корректно
- [ ] Услуги отображаются и фильтруются
- [ ] Страница услуги загружается с правильными данными
- [ ] Портфолио отображается и фильтруется
- [ ] Страница проекта загружается
- [ ] Калькулятор работает корректно
- [ ] Формы отправляются с валидацией
- [ ] Пагинация работает
- [ ] Поиск работает (если есть)

### Адаптивность

- [ ] iPhone SE (375×667)
- [ ] iPhone 12 (390×844)
- [ ] iPad (768×1024)
- [ ] iPad Pro (1024×1366)
- [ ] Desktop (1920×1080)
- [ ] Поворот экрана на мобильных

### Кроссбраузерность

- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge
- [ ] Chrome Android
- [ ] Safari iOS

### Производительность

- [ ] First Contentful Paint < 1.8s
- [ ] Largest Contentful Paint < 2.5s
- [ ] Time to Interactive < 3.8s
- [ ] Cumulative Layout Shift < 0.1
- [ ] Все изображения оптимизированы
- [ ] JS/CSS минифицированы

### SEO

- [ ] Title и description на всех страницах
- [ ] Sitemap.xml доступен
- [ ] Robots.txt настроен
- [ ] Канонические URL
- [ ] Микроразметка Schema.org
- [ ] Open Graph теги

### Безопасность

- [ ] CSRF токены на формах
- [ ] HTTPS принудительно
- [ ] Заголовки безопасности
- [ ] Валидация загружаемых файлов
- [ ] Rate limiting на формах
