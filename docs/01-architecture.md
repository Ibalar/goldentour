# 01. Архитектура приложения

## Общая архитектура

Приложение построено на паттерне MVC с использованием фреймворка Laravel 11. Административная панель реализована на MoonShine 4.

```
┌─────────────────────────────────────────────────────────────┐
│                        Клиент                               │
│              (Браузер / Мобильное устройство)               │
└──────────────────────┬──────────────────────────────────────┘
                       │ HTTP
                       ▼
┌─────────────────────────────────────────────────────────────┐
│                     Nginx (Web Server)                      │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────────┐
│                   Laravel 11 (PHP-FPM)                      │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │ Controllers │  │   Models    │  │   Blade Views       │  │
│  │             │  │  (Eloquent) │  │   + Components      │  │
│  └──────┬──────┘  └──────┬──────┘  └─────────────────────┘  │
│         │                │                                   │
│         └────────────────┘                                   │
│                          │                                   │
│  ┌───────────────────────┴───────────────────────────────┐   │
│  │              MoonShine 4 (Admin Panel)                │   │
│  └───────────────────────────────────────────────────────┘   │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────────┐
│                   MySQL 8.0 (Database)                      │
└─────────────────────────────────────────────────────────────┘
```

## Технологический стек

### Backend

| Компонент | Версия | Назначение |
|-----------|--------|------------|
| PHP | 8.3+ | Язык программирования |
| Laravel | 11.x | Web-фреймворк |
| MoonShine | 4.x | Административная панель |
| MySQL | 8.0+ | Реляционная БД |
| Redis | 7.x | Кэширование и очереди |

### Frontend

| Компонент | Версия | Назначение |
|-----------|--------|------------|
| Blade | — | Шаблонизатор |
| Tailwind CSS | 3.x | Utility-first CSS |
| Alpine.js | 3.x | Реактивность JS |
| Vite | 5.x | Сборка assets |

## Структура базы данных

### ER-диаграмма

```
┌─────────────────────┐       ┌─────────────────────┐
│  service_categories │       │      services       │
├─────────────────────┤       ├─────────────────────┤
│ id (PK)             │◄──────┤ id (PK)             │
│ name                │  1:M  │ category_id (FK)    │
│ slug                │       │ name                │
│ sort_order          │       │ slug                │
│ is_active           │       │ description         │
│ created_at          │       │ price_from          │
│ updated_at          │       │ price_to            │
└─────────────────────┘       │ area_from           │
                              │ area_to             │
                              │ duration            │
                              │ image               │
                              │ is_active           │
                              │ meta_title          │
                              │ meta_description    │
                              └─────────────────────┘

┌─────────────────────┐       ┌─────────────────────┐
│     portfolio       │       │  portfolio_images   │
├─────────────────────┤       ├─────────────────────┤
│ id (PK)             │◄──────┤ id (PK)             │
│ title               │  1:M  │ portfolio_id (FK)   │
│ slug                │       │ image               │
│ description         │       │ sort_order          │
│ client_name         │       │ created_at          │
│ completion_date     │       └─────────────────────┘
│ area                │
│ location            │
│ service_id (FK)     │──────► services
│ thumbnail           │
│ is_active           │
│ meta_title          │
│ meta_description    │
└─────────────────────┘

┌─────────────────────┐       ┌─────────────────────┐
│      reviews        │       │    team_members     │
├─────────────────────┤       ├─────────────────────┤
│ id (PK)             │       │ id (PK)             │
│ author_name         │       │ full_name           │
│ author_phone        │       │ position            │
│ rating              │       │ photo               │
│ text                │       │ bio                 │
│ is_published        │       │ sort_order          │
│ created_at          │       │ is_active           │
│ service_id (FK)     │──────►│ created_at          │
│ portfolio_id (FK)   │──────►│ updated_at          │
└─────────────────────┘       └─────────────────────┘

┌─────────────────────┐       ┌─────────────────────┐
│       leads         │       │      settings       │
├─────────────────────┤       ├─────────────────────┤
│ id (PK)             │       │ id (PK)             │
│ name                │       │ key                 │
│ phone               │       │ value               │
│ email               │       │ type                │
│ message             │       │ group               │
│ service_id (FK)     │──────►│ label               │
│ source              │       │ sort_order          │
│ status              │       │ created_at          │
│ ip_address          │       │ updated_at          │
│ user_agent          │       └─────────────────────┘
│ created_at          │
│ updated_at          │
└─────────────────────┘

┌─────────────────────┐
│       pages         │
├─────────────────────┤
│ id (PK)             │
│ title               │
│ slug                │
│ content             │
│ template            │
│ is_active           │
│ meta_title          │
│ meta_description    │
└─────────────────────┘
```

### Описание таблиц

#### service_categories
Категории услуг (строительство, ремонт, отделка и т.д.)

| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint unsigned | Первичный ключ |
| name | varchar(255) | Название категории |
| slug | varchar(255) | URL-идентификатор (уникальный) |
| description | text | Описание категории |
| icon | varchar(255) | Иконка (опционально) |
| sort_order | int | Порядок сортировки |
| is_active | boolean | Активность |
| timestamps | — | created_at, updated_at |

#### services
Услуги компании

| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint unsigned | Первичный ключ |
| category_id | bigint unsigned | Внешний ключ на категорию |
| name | varchar(255) | Название услуги |
| slug | varchar(255) | URL-идентификатор (уникальный) |
| short_description | text | Краткое описание |
| full_description | longtext | Полное описание |
| price_from | decimal(12,2) | Цена от |
| price_to | decimal(12,2) | Цена до |
| area_from | int | Площадь от (м²) |
| area_to | int | Площадь до (м²) |
| duration | varchar(100) | Срок выполнения |
| image | varchar(255) | Изображение услуги |
| gallery | json | Галерея изображений |
| features | json | Особенности услуги (массив) |
| is_active | boolean | Активность |
| meta_title | varchar(255) | SEO title |
| meta_description | text | SEO description |
| timestamps | — | created_at, updated_at |

#### portfolio
Портфолио выполненных работ

| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint unsigned | Первичный ключ |
| title | varchar(255) | Название объекта |
| slug | varchar(255) | URL-идентификатор |
| description | longtext | Описание проекта |
| client_name | varchar(255) | Имя клиента |
| completion_date | date | Дата завершения |
| area | int | Площадь объекта |
| location | varchar(255) | Адрес/локация |
| service_id | bigint unsigned | Связанная услуга |
| thumbnail | varchar(255) | Обложка |
| before_image | varchar(255) | Фото "до" |
| after_image | varchar(255) | Фото "после" |
| is_featured | boolean | Показывать на главной |
| is_active | boolean | Активность |
| meta_title | varchar(255) | SEO title |
| meta_description | text | SEO description |
| timestamps | — | created_at, updated_at |

#### portfolio_images
Галерея изображений для портфолио

| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint unsigned | Первичный ключ |
| portfolio_id | bigint unsigned | Внешний ключ на портфолио |
| image | varchar(255) | Путь к изображению |
| caption | varchar(255) | Подпись к фото |
| sort_order | int | Порядок сортировки |
| created_at | timestamp | Дата создания |

#### reviews
Отзывы клиентов

| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint unsigned | Первичный ключ |
| author_name | varchar(255) | Имя автора |
| author_phone | varchar(50) | Телефон (для верификации) |
| author_email | varchar(255) | Email |
| rating | tinyint | Рейтинг 1-5 |
| text | text | Текст отзыва |
| is_published | boolean | Опубликован |
| is_verified | boolean | Проверен |
| service_id | bigint unsigned | Связанная услуга (опц.) |
| portfolio_id | bigint unsigned | Связанный проект (опц.) |
| admin_reply | text | Ответ администрации |
| created_at | timestamp | Дата создания |
| updated_at | timestamp | Дата обновления |

#### leads
Заявки от клиентов

| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint unsigned | Первичный ключ |
| name | varchar(255) | Имя клиента |
| phone | varchar(50) | Телефон |
| email | varchar(255) | Email |
| message | text | Сообщение |
| service_id | bigint unsigned | Интересующая услуга |
| calculated_price | decimal(12,2) | Рассчитанная цена |
| calculated_area | int | Рассчитанная площадь |
| source | varchar(50) | Источник (form/calculator/direct) |
| status | enum | new/processing/completed/cancelled |
| ip_address | varchar(45) | IP адрес |
| user_agent | text | User-Agent |
| utm_source | varchar(100) | UTM метки |
| utm_medium | varchar(100) | — |
| utm_campaign | varchar(100) | — |
| created_at | timestamp | Дата создания |
| updated_at | timestamp | Дата обновления |

#### team_members
Сотрудники компании

| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint unsigned | Первичный ключ |
| full_name | varchar(255) | ФИО |
| position | varchar(255) | Должность |
| photo | varchar(255) | Фотография |
| bio | text | Биография |
| phone | varchar(50) | Контактный телефон |
| email | varchar(255) | Email |
| sort_order | int | Порядок сортировки |
| is_active | boolean | Активность |
| timestamps | — | created_at, updated_at |

#### settings
Настройки сайта (key-value)

| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint unsigned | Первичный ключ |
| key | varchar(255) | Ключ настройки (уникальный) |
| value | text | Значение |
| type | enum | string/number/boolean/json/file |
| group | varchar(100) | Группа настроек |
| label | varchar(255) | Человекочитаемое название |
| sort_order | int | Порядок сортировки |
| timestamps | — | created_at, updated_at |

#### pages
Статические страницы

| Поле | Тип | Описание |
|------|-----|----------|
| id | bigint unsigned | Первичный ключ |
| title | varchar(255) | Заголовок страницы |
| slug | varchar(255) | URL-идентификатор |
| content | longtext | Контент (HTML) |
| template | varchar(100) | Шаблон отображения |
| is_active | boolean | Активность |
| meta_title | varchar(255) | SEO title |
| meta_description | text | SEO description |
| timestamps | — | created_at, updated_at |

## Технологические решения

### Кэширование

- **Redis** для кэширования часто запрашиваемых данных (списки услуг, настройки)
- **Кэширование views** для статических страниц
- **CDN** для изображений (опционально)

### Безопасность

- **CSRF-защита** на всех формах
- **Rate limiting** для API и форм заявок
- **SQL-инъекции** — защита через Eloquent ORM
- **XSS-защита** — экранирование вывода в Blade
- **Загрузка файлов** — валидация типов и размеров

### Оптимизация

- **Lazy loading** для изображений
- **Code splitting** через Vite
- **Сжатие assets** (gzip/brotli)
- **Оптимизация изображений** при загрузке
- **Индексы БД** для часто используемых полей

### Интеграции

- **Email** — отправка уведомлений через SMTP
- **Telegram** — уведомления о новых заявках
- **Яндекс.Метрика / Google Analytics** — веб-аналитика
