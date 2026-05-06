# 02. Быстрый старт

## Системные требования

- PHP >= 8.3
- MySQL >= 8.0
- Node.js >= 20.0
- Composer >= 2.0
- Redis (опционально, для кэширования)

## Установка и запуск

### 1. Клонирование репозитория

```bash
git clone https://github.com/ibalar666/goldentour.git
cd goldentour
```

### 2. Установка PHP-зависимостей

```bash
composer install
```

### 3. Установка Node.js-зависимостей

```bash
npm install
```

### 4. Настройка окружения

Скопируйте файл конфигурации:

```bash
cp .env.example .env
```

Отредактируйте `.env` файл:

```env
APP_NAME="Золотой Тур"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=goldentour
DB_USERNAME=root
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="info@goldentour.ru"
MAIL_FROM_NAME="${APP_NAME}"
```

Сгенерируйте ключ приложения:

```bash
php artisan key:generate
```

### 5. Настройка базы данных

Создайте базу данных:

```bash
mysql -u root -p -e "CREATE DATABASE goldentour CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Выполните миграции и заполните тестовыми данными:

```bash
php artisan migrate --seed
```

### 6. Сборка frontend

```bash
npm run build
```

Или для разработки с горячей перезагрузкой:

```bash
npm run dev
```

### 7. Запуск локального сервера

```bash
php artisan serve
```

Сайт будет доступен по адресу: http://localhost:8000

## Данные для входа в админку

После выполнения `php artisan migrate --seed` будут созданы тестовые пользователи:

### Администратор
- **URL:** http://localhost:8000/admin
- **Email:** admin@goldentour.ru
- **Пароль:** password

### Менеджер
- **URL:** http://localhost:8000/admin
- **Email:** manager@goldentour.ru
- **Пароль:** password

## Полезные команды

### Очистка кэша

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Создание символической ссылки для storage

```bash
php artisan storage:link
```

### Запуск тестов

```bash
php artisan test
```

### Генерация sitemap

```bash
php artisan sitemap:generate
```

## Структура тестовых данных

После запуска сидеров (`--seed`) будут созданы:

- 3 категории услуг
- 9 услуг (по 3 в каждой категории)
- 6 объектов портфолио
- 8 отзывов клиентов
- 2 пользователя админ-панели
- Настройки сайта (контакты, соцсети)

## Устранение неполадок

### Ошибка прав доступа

```bash
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/uploads
chown -R www-data:www-data storage bootstrap/cache
```

### Ошибка подключения к БД

Проверьте настройки в `.env` и убедитесь, что MySQL запущен:

```bash
sudo systemctl status mysql
```

### Ошибка 500 после деплоя

```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
