# 07. Этап 4 — Backend (Контроллеры и логика)

## 4.1 Контроллеры

### Создание контроллеров

```bash
php artisan make:controller Frontend/HomeController
php artisan make:controller Frontend/ServiceController
php artisan make:controller Frontend/PortfolioController
php artisan make:controller Frontend/ContactController
php artisan make:controller Frontend/CalculatorController
php artisan make:controller Frontend/LeadController
```

### app/Http/Controllers/Frontend/HomeController.php

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::active()
            ->with('category')
            ->take(6)
            ->get();
        
        $portfolio = Portfolio::active()
            ->featured()
            ->with('service')
            ->take(6)
            ->get();
        
        $reviews = Review::published()
            ->verified()
            ->with('service')
            ->take(3)
            ->latest()
            ->get();
        
        return view('pages.home', compact('services', 'portfolio', 'reviews'));
    }
}
```

### app/Http/Controllers/Frontend/ServiceController.php

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->with(['activeServices' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->get();
        
        return view('pages.services.index', compact('categories'));
    }

    public function show(Service $service)
    {
        $service->load('category');
        
        $relatedServices = Service::active()
            ->where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->take(3)
            ->get();
        
        return view('pages.services.show', compact('service', 'relatedServices'));
    }

    public function category($slug)
    {
        $category = ServiceCategory::where('slug', $slug)
            ->where('is_active', true)
            ->with(['activeServices' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->firstOrFail();
        
        return view('pages.services.category', compact('category'));
    }
}
```

### app/Http/Controllers/Frontend/PortfolioController.php

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Service;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolio = Portfolio::active()
            ->with('service')
            ->latest('completion_date')
            ->paginate(12);
        
        $services = Service::active()
            ->whereHas('portfolios')
            ->get();
        
        return view('pages.portfolio.index', compact('portfolio', 'services'));
    }

    public function show(Portfolio $portfolio)
    {
        $portfolio->load(['service', 'images']);
        
        $relatedProjects = Portfolio::active()
            ->where('service_id', $portfolio->service_id)
            ->where('id', '!=', $portfolio->id)
            ->take(3)
            ->get();
        
        return view('pages.portfolio.show', compact('portfolio', 'relatedProjects'));
    }
}
```

### app/Http/Controllers/Frontend/ContactController.php

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\TeamMember;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contacts');
    }

    public function about()
    {
        $team = TeamMember::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        return view('pages.about', compact('team'));
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        return view('pages.dynamic', compact('page'));
    }
}
```

### app/Http/Controllers/Frontend/CalculatorController.php

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index()
    {
        $services = Service::active()
            ->with('category')
            ->get();
        
        $serviceCategories = ServiceCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->with(['services' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();
        
        return view('pages.calculator', compact('services', 'serviceCategories'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'area' => 'required|integer|min:10|max:1000',
            'options' => 'nullable|array',
        ]);

        $service = Service::findOrFail($request->service_id);
        
        // Базовый расчёт
        $pricePerM2 = $service->price_from / ($service->area_from ?: 1);
        $basePrice = $pricePerM2 * $request->area;
        
        // Коэффициенты за опции
        $optionsMultiplier = 1;
        if ($request->has('options')) {
            foreach ($request->options as $option) {
                $optionsMultiplier += match ($option) {
                    'materials' => 0.3,
                    'design' => 0.15,
                    'urgent' => 0.2,
                    default => 0,
                };
            }
        }
        
        $totalPrice = $basePrice * $optionsMultiplier;
        
        return response()->json([
            'base_price' => round($basePrice, 2),
            'total_price' => round($totalPrice, 2),
            'options_multiplier' => $optionsMultiplier,
            'service' => [
                'name' => $service->name,
                'duration' => $service->duration,
            ],
        ]);
    }
}
```

### app/Http/Controllers/Frontend/LeadController.php

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;
use App\Notifications\LeadCreatedNotification;
use App\Notifications\TelegramLeadNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request)
    {
        $data = $request->validated();
        
        // Добавляем UTM метки из сессии
        $data['utm_source'] = session('utm_source');
        $data['utm_medium'] = session('utm_medium');
        $data['utm_campaign'] = session('utm_campaign');
        
        // Техническая информация
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = $request->userAgent();
        
        $lead = Lead::create($data);
        
        // Отправка уведомлений
        $this->sendNotifications($lead);
        
        return redirect()->back()
            ->with('success', 'Спасибо! Ваша заявка принята. Мы свяжемся с вами в ближайшее время.');
    }

    protected function sendNotifications(Lead $lead): void
    {
        try {
            // Email уведомление администратору
            $adminEmail = \App\Models\Setting::get('admin_email', 'admin@goldentour.ru');
            Notification::route('mail', $adminEmail)
                ->notify(new LeadCreatedNotification($lead));
        } catch (\Exception $e) {
            Log::error('Failed to send email notification: ' . $e->getMessage());
        }

        try {
            // Telegram уведомление
            $telegramToken = \App\Models\Setting::get('telegram_bot_token');
            $telegramChatId = \App\Models\Setting::get('telegram_chat_id');
            
            if ($telegramToken && $telegramChatId) {
                Notification::route('telegram', $telegramChatId)
                    ->notify(new TelegramLeadNotification($lead, $telegramToken));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send telegram notification: ' . $e->getMessage());
        }
    }
}
```

## 4.2 Роуты

### routes/web.php

```php
<?php

use App\Http\Controllers\Frontend\CalculatorController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LeadController;
use App\Http\Controllers\Frontend\PortfolioController;
use App\Http\Controllers\Frontend\ServiceController;
use Illuminate\Support\Facades\Route;

// UTM метки middleware
Route::middleware(['utm.tracking'])->group(function () {
    
    // Главная
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Услуги
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/category/{slug}', [ServiceController::class, 'category'])->name('category');
        Route::get('/{service:slug}', [ServiceController::class, 'show'])->name('show');
    });
    
    // Портфолио
    Route::prefix('portfolio')->name('portfolio.')->group(function () {
        Route::get('/', [PortfolioController::class, 'index'])->name('index');
        Route::get('/{portfolio:slug}', [PortfolioController::class, 'show'])->name('show');
    });
    
    // Калькулятор
    Route::prefix('calculator')->name('calculator.')->group(function () {
        Route::get('/', [CalculatorController::class, 'index'])->name('index');
        Route::post('/calculate', [CalculatorController::class, 'calculate'])->name('calculate');
    });
    Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator');
    
    // Контакты и о компании
    Route::get('/about', [ContactController::class, 'about'])->name('about');
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');
    
    // Динамические страницы
    Route::get('/page/{slug}', [ContactController::class, 'page'])->name('page');
    
    // Формы
    Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
    
});

// Sitemap
Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});

// Robots
Route::get('/robots.txt', function () {
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Sitemap: " . url('/sitemap.xml');
    return response($content)->header('Content-Type', 'text/plain');
});
```

### app/Http/Middleware/UtmTrackingMiddleware.php

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UtmTrackingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $utmParams = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
        
        foreach ($utmParams as $param) {
            if ($request->has($param)) {
                session([$param => $request->get($param)]);
            }
        }
        
        return $next($request);
    }
}
```

### bootstrap/app.php

```php
<?php

use App\Http\Middleware\UtmTrackingMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'utm.tracking' => UtmTrackingMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

## 4.3 Обработка заявок

### app/Http/Requests/StoreLeadRequest.php

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50', 'regex:/^[\+\d\s\-\(\)]{10,20}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
            'service_id' => ['nullable', 'exists:services,id'],
            'calculated_price' => ['nullable', 'numeric'],
            'calculated_area' => ['nullable', 'integer'],
            'source' => ['nullable', Rule::in(['form', 'calculator', 'direct', 'phone'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Пожалуйста, укажите ваше имя',
            'phone.required' => 'Пожалуйста, укажите номер телефона',
            'phone.regex' => 'Введите корректный номер телефона',
            'email.email' => 'Введите корректный email адрес',
        ];
    }
}
```

### app/Notifications/LeadCreatedNotification.php

```php
<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Lead $lead) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Новая заявка с сайта Золотой Тур')
            ->greeting('Здравствуйте!')
            ->line('Поступила новая заявка с сайта.')
            ->line('')
            ->line('**Имя:** ' . $this->lead->name)
            ->line('**Телефон:** ' . $this->lead->phone);

        if ($this->lead->email) {
            $message->line('**Email:** ' . $this->lead->email);
        }

        if ($this->lead->service) {
            $message->line('**Услуга:** ' . $this->lead->service->name);
        }

        if ($this->lead->message) {
            $message->line('**Сообщение:** ' . $this->lead->message);
        }

        if ($this->lead->calculated_price) {
            $message->line('**Рассчитанная цена:** ' . number_format($this->lead->calculated_price, 0, ',', ' ') . ' ₽');
        }

        $message->line('**Источник:** ' . match($this->lead->source) {
            'calculator' => 'Калькулятор',
            'direct' => 'Прямой звонок',
            'phone' => 'Обратный звонок',
            default => 'Форма на сайте',
        });

        $message->action('Открыть в админке', url('/admin/resource/lead-resource/' . $this->lead->id . '/edit'));

        return $message;
    }
}
```

### app/Notifications/TelegramLeadNotification.php

```php
<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramLeadNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Lead $lead,
        public string $token
    ) {}

    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram(object $notifiable): TelegramMessage
    {
        $text = "🔔 *Новая заявка с сайта*\n\n";
        $text .= "👤 *Имя:* {$this->lead->name}\n";
        $text .= "📞 *Телефон:* {$this->lead->phone}\n";
        
        if ($this->lead->email) {
            $text .= "📧 *Email:* {$this->lead->email}\n";
        }
        
        if ($this->lead->service) {
            $text .= "🔨 *Услуга:* {$this->lead->service->name}\n";
        }
        
        if ($this->lead->calculated_price) {
            $price = number_format($this->lead->calculated_price, 0, ',', ' ');
            $text .= "💰 *Расчёт:* {$price} ₽\n";
        }
        
        $source = match($this->lead->source) {
            'calculator' => '🧮 Калькулятор',
            'direct' => '📞 Прямой звонок',
            default => '📝 Форма на сайте',
        };
        $text .= "📌 *Источник:* {$source}";

        return TelegramMessage::create()
            ->to($notifiable->routes['telegram'])
            ->token($this->token)
            ->content($text)
            ->button('Открыть в админке', url('/admin/resource/lead-resource/' . $this->lead->id . '/edit'));
    }
}
```

## 4.4 Калькулятор на JavaScript

### resources/js/components/calculator.js

```javascript
export default () => ({
    step: 1,
    serviceId: null,
    service: null,
    area: 50,
    options: [],
    result: null,
    loading: false,
    error: null,

    init() {
        // Парсим параметры URL
        const params = new URLSearchParams(window.location.search);
        const serviceSlug = params.get('service');
        if (serviceSlug) {
            this.selectServiceBySlug(serviceSlug);
        }
    },

    selectServiceBySlug(slug) {
        const select = document.querySelector('select[name="service"]');
        if (select) {
            const option = select.querySelector(`option[data-slug="${slug}"]`);
            if (option) {
                this.serviceId = option.value;
                this.service = JSON.parse(option.dataset.info || '{}');
                this.step = 2;
            }
        }
    },

    nextStep() {
        if (this.step < 3) {
            this.step++;
        }
    },

    prevStep() {
        if (this.step > 1) {
            this.step--;
        }
    },

    onServiceChange() {
        const select = document.querySelector('select[name="service"]');
        const option = select.options[select.selectedIndex];
        this.service = JSON.parse(option.dataset.info || '{}');
    },

    async calculate() {
        this.loading = true;
        this.error = null;

        try {
            const response = await fetch('/calculator/calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    service_id: this.serviceId,
                    area: this.area,
                    options: this.options,
                }),
            });

            if (!response.ok) {
                throw new Error('Ошибка расчёта');
            }

            this.result = await response.json();
            this.step = 4;
        } catch (err) {
            this.error = err.message;
        } finally {
            this.loading = false;
        }
    },

    formatPrice(price) {
        return new Intl.NumberFormat('ru-RU').format(Math.round(price));
    },

    get progress() {
        return (this.step / 4) * 100;
    },

    get canProceed() {
        switch (this.step) {
            case 1:
                return !!this.serviceId;
            case 2:
                return this.area >= 10 && this.area <= 1000;
            default:
                return true;
        }
    },
});
```

### Использование в Blade

```html
<div x-data="calculator()" x-init="init()">
    <!-- Progress bar -->
    <div class="w-full bg-secondary-200 rounded-full h-2 mb-8">
        <div class="bg-primary-600 h-2 rounded-full transition-all duration-300" 
             :style="`width: ${progress}%`"></div>
    </div>

    <!-- Step 1: Service -->
    <div x-show="step === 1" x-transition>
        <h3 class="text-lg font-semibold mb-4">Выберите услугу</h3>
        <select x-model="serviceId" @change="onServiceChange()" class="...">
            <option value="">-- Выберите --</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}" 
                        data-slug="{{ $service->slug }}"
                        data-info="{{ json_encode(['name' => $service->name, 'price_from' => $service->price_from]) }}">
                    {{ $service->name }}
                </option>
            @endforeach
        </select>
        <button @click="nextStep()" :disabled="!canProceed" class="...">Далее</button>
    </div>

    <!-- Step 2: Area -->
    <div x-show="step === 2" x-transition style="display: none;">
        <h3 class="text-lg font-semibold mb-4">Укажите площадь</h3>
        <input type="range" x-model="area" min="10" max="500" step="5" class="...">
        <span x-text="area + ' м²'"></span>
        <div class="flex gap-4">
            <button @click="prevStep()" class="...">Назад</button>
            <button @click="nextStep()" class="...">Далее</button>
        </div>
    </div>

    <!-- Step 3: Options -->
    <div x-show="step === 3" x-transition style="display: none;">
        <h3 class="text-lg font-semibold mb-4">Дополнительные опции</h3>
        <!-- Checkboxes -->
        <div class="flex gap-4">
            <button @click="prevStep()" class="...">Назад</button>
            <button @click="calculate()" :disabled="loading" class="...">
                <span x-show="!loading">Рассчитать</span>
                <span x-show="loading">Расчёт...</span>
            </button>
        </div>
    </div>

    <!-- Step 4: Result -->
    <div x-show="step === 4 && result" x-transition style="display: none;">
        <div class="bg-primary-50 p-6 rounded-xl text-center">
            <p class="text-secondary-600">Примерная стоимость:</p>
            <p class="text-4xl font-bold text-primary-600" x-text="formatPrice(result.total_price) + ' ₽'"></p>
        </div>
        <!-- Lead form -->
    </div>
</div>
```
