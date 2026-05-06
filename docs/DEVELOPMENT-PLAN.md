# План разработки сайта «Золотой Тур»

## Сводная таблица этапов

| Этап | Название | Время | Основные задачи | Результат |
|------|----------|-------|-----------------|-----------|
| 0 | Подготовка | 4ч | Laravel, MoonShine, Tailwind, Git | Рабочее окружение |
| 1 | База данных | 6ч | Миграции, модели, связи, сидеры | Структура БД + тестовые данные |
| 2 | Админ-панель | 20ч | Ресурсы MoonShine, кастомные действия | Полноценная админка |
| 3 | Frontend | 40ч | Blade, Tailwind, компоненты, страницы | Вёрстка всех страниц |
| 4 | Backend | 30ч | Контроллеры, формы, уведомления | Рабочий функционал |
| 5 | SEO | 6ч | ЧПУ, мета-теги, sitemap | Оптимизация под поисковики |
| 6 | Адаптив и тестирование | 20ч | Адаптив, кроссбраузерность, тесты | Готовый к запуску продукт |
| 7 | Контент | 8ч | Наполнение текстами и фото | Заполненный сайт |

**Итого: 160–200 часов (≈25 рабочих дней при 8ч/день)**

---

## Этап 0: Подготовка (4 часа)

### 0.1 Установка и настройка проекта (2ч)

```bash
# Создание проекта Laravel 12
composer create-project laravel/laravel goldentour "12.*"
cd goldentour

# Установка MoonShine 4
composer require moonshine/moonshine:^4.0
php artisan moonshine:install

# Настройка Tailwind CSS
npm install -D tailwindcss postcss autoprefixer @tailwindcss/forms
npx tailwindcss init -p

# Установка Alpine.js
npm install alpinejs
```

**Что проверить:**
- [ ] Laravel открывается по localhost:8000
- [ ] MoonShine доступна по /admin
- [ ] Tailwind стили применяются
- [ ] Alpine.js работает

### 0.2 Настройка Git (1ч)

```bash
git init
git remote add origin https://github.com/ibalar666/goldentour.git

# Создать .gitignore
cat > .gitignore << 'EOF'
/node_modules
/public/build
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
npm-debug.log
yarn-error.log
/.idea
/.vscode
/public/uploads/
EOF

git add .
git commit -m "Initial commit: Laravel 12 + MoonShine 4 + Tailwind setup"
git push -u origin main
```

### 0.3 Подготовка домена и хостинга (1ч)

- Настройка DNS
- SSL сертификат
- Настройка Nginx

---

## Этап 1: База данных (6 часов)

### 1.1 Создание миграций (2ч)

```bash
# Категории и услуги
php artisan make:migration create_service_categories_table
php artisan make:migration create_services_table

# Портфолио
php artisan make:migration create_portfolios_table
php artisan make:migration create_portfolio_images_table

# Отзывы и заявки
php artisan make:migration create_reviews_table
php artisan make:migration create_leads_table

# Служебные
php artisan make:migration create_team_members_table
php artisan make:migration create_settings_table
php artisan make:migration create_pages_table

# Выполнить
php artisan migrate
```

### 1.2 Создание моделей и связей (2ч)

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

### 1.3 Создание сидеров (2ч)

```bash
php artisan make:seeder ServiceCategorySeeder
php artisan make:seeder ServiceSeeder
php artisan make:seeder PortfolioSeeder
php artisan make:seeder ReviewSeeder
php artisan make:seeder SettingSeeder
php artisan make:seeder TeamMemberSeeder

# Заполнить данными и выполнить
php artisan migrate --seed
```

**Результат:** Рабочая БД с тестовыми данными

---

## Этап 2: Админ-панель MoonShine (20 часов)

### 2.1 Создание ресурсов (10ч)

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

### 2.2 Настройка полей и связей (6ч)

Для каждого ресурса настроить:
- Поля форм
- Таблицы списков
- Фильтры
- Поиск
- Сортировку

### 2.3 Кастомные действия (4ч)

- Экспорт заявок в CSV/Excel
- Массовые операции с отзывами
- Уведомления о новых заявках

**Результат:** Полноценная админ-панель для управления контентом

---

## Этап 3: Frontend (40 часов)

### 3.1 Базовый шаблон (4ч)

- layouts/app.blade.php
- partials/header.blade.php (с мобильным меню)
- partials/footer.blade.php
- components/seo-meta.blade.php

### 3.2 Компоненты (6ч)

```bash
# Blade компоненты
php artisan make:component Button
php artisan make:component CardService
php artisan make:component PortfolioCard
php artisan make:component ReviewCard
php artisan make:component LeadForm
```

### 3.3 Страницы (30ч)

| Страница | Время | Сложность |
|----------|-------|-----------|
| Главная | 6ч | Высокая |
| Список услуг | 3ч | Средняя |
| Страница услуги | 4ч | Средняя |
| Портфолио (список) | 3ч | Средняя |
| Портфолио (деталь) | 4ч | Средняя |
| О компании | 2ч | Низкая |
| Контакты | 2ч | Низкая |
| Калькулятор | 6ч | Высокая |

**Результат:** Полностью свёрстанный сайт

---

## Этап 4: Backend (30 часов)

### 4.1 Контроллеры (8ч)

```bash
php artisan make:controller Frontend/HomeController
php artisan make:controller Frontend/ServiceController
php artisan make:controller Frontend/PortfolioController
php artisan make:controller Frontend/ContactController
php artisan make:controller Frontend/CalculatorController
php artisan make:controller Frontend/LeadController
```

### 4.2 Роуты и маршрутизация (4ч)

```php
// routes/web.php
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{portfolio:slug}', [PortfolioController::class, 'show'])->name('portfolio.show');
Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator');
Route::post('/calculator/calculate', [CalculatorController::class, 'calculate'])->name('calculator.calculate');
Route::get('/about', [ContactController::class, 'about'])->name('about');
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
```

### 4.3 Обработка заявок (8ч)

- Валидация форм (Form Request)
- Сохранение в БД
- Email уведомления
- Telegram уведомления

### 4.4 Калькулятор (6ч)

- Backend логика расчёта
- API endpoint
- Интеграция с формой заявки

### 4.5 Прочий функционал (4ч)

- UTM-метки
- Кэширование
- Обработка 404

**Результат:** Полностью работающий сайт

---

## Этап 5: SEO (6 часов)

### 5.1 ЧПУ и slug (1ч)

```php
// Trait для моделей
trait HasSlug {
    public function getRouteKeyName() {
        return 'slug';
    }
}
```

### 5.2 Мета-теги (2ч)

- Компонент SeoMeta
- Динамические title/description
- Open Graph теги

### 5.3 Sitemap и robots.txt (2ч)

```bash
php artisan make:command GenerateSitemap
```

### 5.4 Микроразметка (1ч)

- Schema.org Organization
- Schema.org Service
- BreadcrumbList

**Результат:** SEO-оптимизированный сайт

---

## Этап 6: Адаптив и тестирование (20 часов)

### 6.1 Адаптивная вёрстка (8ч)

- Mobile-first подход
- Breakpoints: 320, 480, 768, 1024, 1280+
- Тестирование на реальных устройствах

### 6.2 Кроссбраузерность (4ч)

- Chrome, Firefox, Safari, Edge
- iOS Safari, Chrome Android
- Polyfills при необходимости

### 6.3 Функциональное тестирование (8ч)

```bash
# PHPUnit тесты
php artisan make:test ServiceTest
php artisan make:test LeadFormTest
php artisan make:test CalculatorTest

# Dusk тесты
php artisan dusk:make NavigationTest
php artisan dusk:make LeadFormBrowserTest
```

**Чек-лист:**
- [ ] Все формы работают
- [ ] Калькулятор считает корректно
- [ ] Админка функциональна
- [ ] Уведомления отправляются
- [ ] Файлы загружаются
- [ ] Страницы открываются без ошибок

**Результат:** Готовый к продакшену продукт

---

## Этап 7: Контент (8 часов)

### 7.1 Настройки сайта (1ч)

Заполнить в админке:
- Контакты компании
- Социальные сети
- SEO-настройки
- Баннеры

### 7.2 Услуги (3ч)

- 3-5 категорий
- 3-5 услуг в каждой
- Уникальные тексты
- Фотографии

### 7.3 Портфолио (2ч)

- 6-12 проектов
- Фото "до" и "после"
- Описания

### 7.4 Отзывы и команда (2ч)

- 5-10 отзывов
- 3-7 сотрудников

**Результат:** Заполненный сайт, готовый к запуску

---

## Команды для каждого этапа

### Этап 0
```bash
composer create-project laravel/laravel goldentour "12.*"
composer require moonshine/moonshine:^4.0
npm install -D tailwindcss postcss autoprefixer alpinejs
```

### Этап 1
```bash
php artisan make:migration create_services_table
php artisan make:model Service
php artisan make:seeder ServiceSeeder
php artisan migrate --seed
```

### Этап 2
```bash
php artisan moonshine:resource ServiceResource --model=Service
php artisan moonshine:resource PortfolioResource --model=Portfolio
```

### Этап 3
```bash
php artisan make:component Button
php artisan make:component CardService
```

### Этап 4
```bash
php artisan make:controller Frontend/HomeController
php artisan make:request StoreLeadRequest
php artisan make:notification LeadCreatedNotification
```

### Этап 5
```bash
php artisan make:command GenerateSitemap
php artisan sitemap:generate
```

### Этап 6
```bash
php artisan test
php artisan dusk
npm run lighthouse
```

### Этап 7
```bash
# Ручное заполнение через админку
# http://localhost:8000/admin
```

---

## Риски и решения

| Риск | Вероятность | Влияние | Решение |
|------|-------------|---------|---------|
| Задержка контента от клиента | Высокая | Среднее | Использовать placeholder'ы |
| Изменение ТЗ | Средняя | Высокое | Фиксировать требования |
| Проблемы с хостингом | Низкая | Высокое | Тестовый деплой заранее |
| Баги в MoonShine 4 | Средняя | Среднее | Обновления, workaround'ы |

---

## Итоговый чек-лист запуска

- [ ] Все этапы завершены
- [ ] Тесты проходят
- [ ] Контент заполнен
- [ ] SEO настроено
- [ ] SSL работает
- [ ] Формы отправляют уведомления
- [ ] Резервное копирование настроено
- [ ] Google Analytics подключен
- [ ] Яндекс.Метрика подключена
