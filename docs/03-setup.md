# 03. Этап 0 — Подготовка проекта

## 0.1 Настройка проекта

### Создание Laravel 12 проекта

```bash
# Установка через Composer
composer create-project laravel/laravel goldentour "12.*"
cd goldentour
```

### Настройка .env

```env
APP_NAME="Золотой Тур"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=Europe/Moscow
APP_LOCALE=ru

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=goldentour
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="info@goldentour.ru"
MAIL_FROM_NAME="${APP_NAME}"

# Настройки MoonShine
MOONSHINE_TITLE="Золотой Тур - Админка"
MOONSHINE_LOGO="/images/logo-admin.png"
```

### Установка MoonShine 4

```bash
composer require moonshine/moonshine:^4.0

# Установка с настройками
php artisan moonshine:install

# Выберите:
# - Установить пакет: yes
# - Установить Laravel Lang: yes
# - Создать SuperUser: yes
```

При создании SuperUser:
- Имя: Администратор
- Email: admin@goldentour.ru
- Пароль: password

### Настройка Tailwind CSS + Vite

```bash
# Установка зависимостей
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

**tailwind.config.js:**

```javascript
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/moonshine/moonshine/resources/views/**/*.blade.php",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                secondary: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                },
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
```

**resources/css/app.css:**

```css
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
    html {
        scroll-behavior: smooth;
    }
    
    body {
        @apply font-sans text-secondary-800 antialiased;
    }
}

@layer components {
    .btn {
        @apply inline-flex items-center justify-center px-6 py-3 rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2;
    }
    
    .btn-primary {
        @apply btn bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500;
    }
    
    .btn-secondary {
        @apply btn bg-secondary-200 text-secondary-800 hover:bg-secondary-300 focus:ring-secondary-500;
    }
    
    .section-title {
        @apply text-3xl md:text-4xl font-bold text-secondary-900 mb-4;
    }
    
    .section-subtitle {
        @apply text-lg text-secondary-600 max-w-2xl;
    }
}
```

**vite.config.js:**

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: 'localhost',
        port: 5173,
    },
});
```

### Установка Alpine.js

```bash
npm install alpinejs
```

**resources/js/app.js:**

```javascript
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Дополнительные компоненты
import './components/mobile-menu';
import './components/calculator';
import './components/slider';
```

## 0.2 Настройка Git

### Инициализация репозитория

```bash
git init
git remote add origin https://github.com/ibalar666/goldentour.git
```

### .gitignore

```gitignore
/node_modules
/public/build
/public/hot
/public/storage
/storage/*.key
/storage/pail
/vendor
.env
.env.backup
.env.production
.phpactor.json
.phpunit.result.cache
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
/auth.json
/.fleet
/.idea
/.nova
/.vscode
/.zed
/public/uploads/
*.log
.DS_Store
```

### Первый коммит

```bash
git add .
git commit -m "Initial commit: Laravel 12 + MoonShine 4 + Tailwind setup"
git push -u origin main
```

## 0.3 Домен и хостинг

### Требования к хостингу

- PHP 8.3+
- MySQL 8.0+
- Composer
- Node.js 20+ (для сборки)
- SSH доступ
- SSL сертификат

### Конфигурация Nginx

```nginx
server {
    listen 80;
    server_name goldentour.ru www.goldentour.ru;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name goldentour.ru www.goldentour.ru;

    root /var/www/goldentour/public;
    index index.php index.html;

    ssl_certificate /etc/letsencrypt/live/goldentour.ru/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/goldentour.ru/privkey.pem;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
```

### Права доступа на продакшене

```bash
sudo chown -R www-data:www-data /var/www/goldentour
sudo chmod -R 755 /var/www/goldentour/storage
sudo chmod -R 755 /var/www/goldentour/bootstrap/cache
```

### Деплой скрипт

**deploy.sh:**

```bash
#!/bin/bash

set -e

cd /var/www/goldentour

echo "⬇️ Pulling latest changes..."
git pull origin main

echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "📦 Installing Node dependencies..."
npm ci

echo "🔨 Building assets..."
npm run build

echo "🔄 Running migrations..."
php artisan migrate --force

echo "🗑️ Clearing caches..."
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🔗 Storage link..."
php artisan storage:link

echo "✅ Deployment completed!"
```

### Проверка после установки

1. Откройте сайт: https://goldentour.ru
2. Проверьте админку: https://goldentour.ru/admin
3. Проверьте формы обратной связи
4. Проверьте загрузку изображений
