# 06. Этап 3 — Frontend (Blade + Tailwind + Alpine.js)

## 3.1 Базовый шаблон

### resources/views/layouts/app.blade.php

```html
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    @hasSection('meta_title')
        <title>@yield('meta_title')</title>
        <meta property="og:title" content="@yield('meta_title')">
    @else
        <title>{{ \App\Models\Setting::get('site_title', 'Золотой Тур') }}</title>
    @endif
    
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
        <meta property="og:description" content="@yield('meta_description')">
    @else
        <meta name="description" content="{{ \App\Models\Setting::get('site_description', '') }}">
    @endif
    
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-white">
    <!-- Header -->
    @include('partials.header')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('partials.footer')
    
    @stack('scripts')
</body>
</html>
```

### resources/views/partials/header.blade.php

```html
<header class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-primary-600">
                    Золотой Тур
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('services.index') }}" class="text-secondary-600 hover:text-primary-600 font-medium transition">
                    Услуги
                </a>
                <a href="{{ route('portfolio.index') }}" class="text-secondary-600 hover:text-primary-600 font-medium transition">
                    Портфолио
                </a>
                <a href="{{ route('about') }}" class="text-secondary-600 hover:text-primary-600 font-medium transition">
                    О компании
                </a>
                <a href="{{ route('contacts') }}" class="text-secondary-600 hover:text-primary-600 font-medium transition">
                    Контакты
                </a>
                <a href="{{ route('calculator') }}" class="btn-primary text-sm">
                    Калькулятор
                </a>
            </nav>
            
            <!-- Mobile Menu Button -->
            <div class="flex items-center md:hidden">
                <button 
                    @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="text-secondary-600 hover:text-primary-600 p-2"
                    aria-label="Toggle menu"
                >
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden bg-white border-t"
        style="display: none;"
    >
        <div class="px-4 pt-2 pb-4 space-y-1">
            <a href="{{ route('services.index') }}" class="block py-2 text-secondary-600 hover:text-primary-600 font-medium">
                Услуги
            </a>
            <a href="{{ route('portfolio.index') }}" class="block py-2 text-secondary-600 hover:text-primary-600 font-medium">
                Портфолио
            </a>
            <a href="{{ route('about') }}" class="block py-2 text-secondary-600 hover:text-primary-600 font-medium">
                О компании
            </a>
            <a href="{{ route('contacts') }}" class="block py-2 text-secondary-600 hover:text-primary-600 font-medium">
                Контакты
            </a>
            <a href="{{ route('calculator') }}" class="block py-2 text-primary-600 font-medium">
                Калькулятор
            </a>
        </div>
    </div>
</header>
```

### resources/views/partials/footer.blade.php

```html
<footer class="bg-secondary-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div>
                <h3 class="text-xl font-bold text-primary-400 mb-4">Золотой Тур</h3>
                <p class="text-secondary-400 text-sm mb-4">
                    Профессиональное строительство и ремонт. Работаем с 2010 года.
                </p>
                <div class="flex space-x-4">
                    @if($vk = \App\Models\Setting::get('social_vk'))
                        <a href="{{ $vk }}" target="_blank" class="text-secondary-400 hover:text-primary-400 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm6.002 15.56c.622.622 1.254 1.264 1.78 1.79.242.242.435.496.576.753.14.257.21.502.21.727 0 .25-.062.478-.186.683-.124.205-.29.363-.497.474-.207.11-.45.166-.73.166-.28 0-.57-.05-.87-.15-.3-.1-.603-.25-.91-.45-.307-.2-.616-.44-.928-.72-.312-.28-.612-.57-.9-.87l-1.98 1.98c-.22.22-.44.38-.66.48-.22.1-.46.15-.72.15-.26 0-.52-.05-.78-.15-.26-.1-.51-.24-.75-.42-.24-.18-.45-.4-.63-.66-.18-.26-.31-.55-.39-.87-.08-.32-.12-.67-.12-1.05 0-.38.04-.76.12-1.14.08-.38.22-.73.42-1.05.2-.32.48-.59.84-.81.36-.22.82-.33 1.38-.33.26 0 .51.03.75.09.24.06.45.14.63.24.18.1.33.21.45.33.12.12.2.24.24.36l-.9.9c-.12-.16-.28-.3-.48-.42-.2-.12-.44-.18-.72-.18-.28 0-.52.08-.72.24-.2.16-.36.38-.48.66-.12.28-.18.6-.18.96 0 .36.06.68.18.96.12.28.28.5.48.66.2.16.44.24.72.24.28 0 .52-.06.72-.18.2-.12.36-.26.48-.42l2.58-2.58c.36-.36.72-.66 1.08-.9.36-.24.72-.42 1.08-.54.36-.12.72-.18 1.08-.18.36 0 .68.06.96.18.28.12.52.3.72.54.2.24.36.54.48.9.12.36.18.78.18 1.26 0 .36-.04.72-.12 1.08-.08.36-.2.7-.36 1.02-.16.32-.36.6-.6.84-.24.24-.52.42-.84.54-.32.12-.68.18-1.08.18-.4 0-.78-.06-1.14-.18-.36-.12-.7-.3-1.02-.54-.32-.24-.64-.54-.96-.9-.32-.36-.66-.78-1.02-1.26z"/></svg>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Services -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Услуги</h4>
                <ul class="space-y-2">
                    @foreach(\App\Models\ServiceCategory::where('is_active', true)->orderBy('sort_order')->get() as $category)
                        <li>
                            <a href="{{ route('services.category', $category->slug) }}" class="text-secondary-400 hover:text-primary-400 transition text-sm">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <!-- Contacts -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Контакты</h4>
                <ul class="space-y-2 text-sm text-secondary-400">
                    <li>{{ \App\Models\Setting::get('company_phone', '+7 (XXX) XXX-XX-XX') }}</li>
                    <li>{{ \App\Models\Setting::get('company_email', 'info@goldentour.ru') }}</li>
                    <li>{{ \App\Models\Setting::get('company_address', 'г. Минск') }}</li>
                    <li>{{ \App\Models\Setting::get('company_work_hours', 'Пн-Пт: 9:00-18:00') }}</li>
                </ul>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Быстрые ссылки</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('calculator') }}" class="text-secondary-400 hover:text-primary-400 transition text-sm">Калькулятор</a></li>
                    <li><a href="{{ route('portfolio.index') }}" class="text-secondary-400 hover:text-primary-400 transition text-sm">Портфолио</a></li>
                    <li><a href="{{ route('contacts') }}" class="text-secondary-400 hover:text-primary-400 transition text-sm">Обратная связь</a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-secondary-800 mt-8 pt-8 text-center text-sm text-secondary-500">
            <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('company_name', 'Золотой Тур') }}. Все права защищены.</p>
        </div>
    </div>
</footer>
```

## 3.2 Blade Компоненты

### resources/views/components/button.blade.php

```html
@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
])

@php
$classes = match($variant) {
    'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500',
    'secondary' => 'bg-secondary-200 text-secondary-800 hover:bg-secondary-300 focus:ring-secondary-500',
    'outline' => 'border-2 border-primary-600 text-primary-600 hover:bg-primary-50 focus:ring-primary-500',
    default => 'bg-primary-600 text-white hover:bg-primary-700',
};

$sizes = match($size) {
    'sm' => 'px-4 py-2 text-sm',
    'md' => 'px-6 py-3 text-base',
    'lg' => 'px-8 py-4 text-lg',
    default => 'px-6 py-3',
};

$baseClasses = 'inline-flex items-center justify-center rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $classes . ' ' . $sizes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $classes . ' ' . $sizes]) }}>
        {{ $slot }}
    </button>
@endif
```

### resources/views/components/card-service.blade.php

```html
@props(['service'])

<div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
    @if($service->image)
        <div class="relative h-48 overflow-hidden">
            <img 
                src="{{ asset('storage/' . $service->image) }}" 
                alt="{{ $service->name }}" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            >
            @if($service->price_from)
                <div class="absolute bottom-4 right-4 bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                    от {{ number_format($service->price_from, 0, ',', ' ') }} ₽
                </div>
            @endif
        </div>
    @endif
    
    <div class="p-6">
        <h3 class="text-xl font-bold text-secondary-900 mb-2">
            <a href="{{ route('services.show', $service->slug) }}" class="hover:text-primary-600 transition">
                {{ $service->name }}
            </a>
        </h3>
        
        @if($service->short_description)
            <p class="text-secondary-600 text-sm mb-4 line-clamp-2">
                {{ $service->short_description }}
            </p>
        @endif
        
        @if($service->features && count($service->features) > 0)
            <ul class="space-y-1 mb-4">
                @foreach(array_slice($service->features, 0, 3) as $feature)
                    <li class="flex items-center text-sm text-secondary-600">
                        <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ $feature }}
                    </li>
                @endforeach
            </ul>
        @endif
        
        <x-button href="{{ route('services.show', $service->slug) }}" variant="outline" size="sm" class="w-full">
            Подробнее
        </x-button>
    </div>
</div>
```

### resources/views/components/portfolio-card.blade.php

```html
@props(['item'])

<div class="group relative overflow-hidden rounded-xl">
    <img 
        src="{{ asset('storage/' . $item->thumbnail) }}" 
        alt="{{ $item->title }}" 
        class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500"
    >
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <div class="absolute bottom-0 left-0 right-0 p-6">
            <span class="text-primary-400 text-sm font-medium">{{ $item->service?->name }}</span>
            <h3 class="text-white text-xl font-bold mt-1">{{ $item->title }}</h3>
            @if($item->location)
                <p class="text-gray-300 text-sm mt-1">{{ $item->location }}</p>
            @endif
            <a href="{{ route('portfolio.show', $item->slug) }}" class="inline-flex items-center text-white mt-3 hover:text-primary-400 transition">
                Смотреть проект
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
```

### resources/views/components/review-card.blade.php

```html
@props(['review'])

<div class="bg-secondary-50 rounded-xl p-6">
    <div class="flex items-center mb-4">
        <div class="flex text-primary-400">
            @for($i = 1; $i <= 5; $i++)
                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'fill-current' : 'text-secondary-300' }}" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endfor
        </div>
        @if($review->is_verified)
            <span class="ml-3 text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                Проверен
            </span>
        @endif
    </div>
    
    <p class="text-secondary-700 mb-4">"{{ $review->text }}"</p>
    
    <div class="flex items-center">
        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 font-bold">
            {{ mb_substr($review->author_name, 0, 1) }}
        </div>
        <div class="ml-3">
            <p class="font-semibold text-secondary-900">{{ $review->author_name }}</p>
            @if($review->service)
                <p class="text-sm text-secondary-500">{{ $review->service->name }}</p>
            @endif
        </div>
    </div>
    
    @if($review->admin_reply)
        <div class="mt-4 pt-4 border-t border-secondary-200">
            <p class="text-sm text-secondary-600">
                <span class="font-semibold">Ответ компании:</span>
                {{ $review->admin_reply }}
            </p>
        </div>
    @endif
</div>
```

### resources/views/components/lead-form.blade.php

```html
@props(['service' => null, 'source' => 'form'])

<form 
    action="{{ route('leads.store') }}" 
    method="POST" 
    x-data="{ submitted: false }"
    @submit.prevent="
        submitted = true;
        $el.submit();
    "
    class="space-y-4"
>
    @csrf
    <input type="hidden" name="source" value="{{ $source }}">
    
    @if($service)
        <input type="hidden" name="service_id" value="{{ $service->id }}">
    @endif
    
    <div>
        <label for="name" class="block text-sm font-medium text-secondary-700 mb-1">Ваше имя *</label>
        <input 
            type="text" 
            id="name" 
            name="name" 
            required
            class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="Иван Иванов"
        >
    </div>
    
    <div>
        <label for="phone" class="block text-sm font-medium text-secondary-700 mb-1">Телефон *</label>
        <input 
            type="tel" 
            id="phone" 
            name="phone" 
            required
            x-mask="+7 (999) 999-99-99"
            class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="+7 (___) ___-__-__"
        >
    </div>
    
    <div>
        <label for="email" class="block text-sm font-medium text-secondary-700 mb-1">Email</label>
        <input 
            type="email" 
            id="email" 
            name="email"
            class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="example@email.com"
        >
    </div>
    
    <div>
        <label for="message" class="block text-sm font-medium text-secondary-700 mb-1">Сообщение</label>
        <textarea 
            id="message" 
            name="message" 
            rows="4"
            class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="Опишите ваш проект..."
        ></textarea>
    </div>
    
    <x-button type="submit" variant="primary" class="w-full" ::disabled="submitted">
        <span x-show="!submitted">Отправить заявку</span>
        <span x-show="submitted" style="display: none;">Отправка...</span>
    </x-button>
    
    <p class="text-xs text-secondary-500 text-center">
        Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности
    </p>
</form>
```

## 3.3 Страницы сайта

### resources/views/pages/home.blade.php

```html
@extends('layouts.app')

@section('meta_title', \App\Models\Setting::get('site_title'))
@section('meta_description', \App\Models\Setting::get('site_description'))

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-secondary-900 py-20 lg:py-32 overflow-hidden">
        <div class="absolute inset-0">
            <img 
                src="{{ asset('storage/' . \App\Models\Setting::get('hero_image', 'images/hero-bg.jpg')) }}" 
                alt="" 
                class="w-full h-full object-cover opacity-40"
            >
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">
                    {{ \App\Models\Setting::get('hero_title', 'Строительство и ремонт под ключ') }}
                </h1>
                <p class="text-xl text-secondary-300 mb-8">
                    {{ \App\Models\Setting::get('hero_subtitle', 'Качественно, в срок и по честной цене') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <x-button href="{{ route('calculator') }}" size="lg">
                        {{ \App\Models\Setting::get('hero_button_text', 'Получить консультацию') }}
                    </x-button>
                    <x-button href="{{ route('services.index') }}" variant="outline" size="lg">
                        Наши услуги
                    </x-button>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="section-title">Наши услуги</h2>
                <p class="section-subtitle mx-auto">Полный спектр строительных и отделочных работ</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $service)
                    <x-card-service :service="$service" />
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <x-button href="{{ route('services.index') }}" variant="outline">
                    Все услуги
                </x-button>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="py-16 lg:py-24 bg-secondary-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="section-title">Выполненные проекты</h2>
                <p class="section-subtitle mx-auto">Посмотрите примеры наших работ</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($portfolio as $item)
                    <x-portfolio-card :item="$item" />
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <x-button href="{{ route('portfolio.index') }}" variant="outline">
                    Все проекты
                </x-button>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="section-title">Отзывы клиентов</h2>
                <p class="section-subtitle mx-auto">Что говорят о нас наши заказчики</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reviews as $review)
                    <x-review-card :review="$review" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 lg:py-24 bg-primary-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Готовы начать проект?</h2>
                    <p class="text-primary-100 text-lg mb-8">
                        Получите бесплатную консультацию и расчёт стоимости работ
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="tel:{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('company_phone')) }}" class="inline-flex items-center justify-center px-6 py-3 bg-white text-primary-600 rounded-lg font-medium hover:bg-primary-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            {{ \App\Models\Setting::get('company_phone') }}
                        </a>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 lg:p-8 shadow-xl">
                    <h3 class="text-xl font-bold text-secondary-900 mb-6">Оставить заявку</h3>
                    <x-lead-form />
                </div>
            </div>
        </div>
    </section>
@endsection
```

### resources/views/pages/services/index.blade.php

```html
@extends('layouts.app')

@section('meta_title', 'Услуги строительной компании Золотой Тур')
@section('meta_description', 'Полный спектр строительных и отделочных услуг. Строительство домов, ремонт квартир, отделка помещений.')

@section('content')
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="section-title">Наши услуги</h1>
                <p class="section-subtitle mx-auto">Профессиональное выполнение всех видов строительных работ</p>
            </div>
            
            @foreach($categories as $category)
                <div class="mb-16">
                    <h2 class="text-2xl font-bold text-secondary-900 mb-6 flex items-center">
                        @if($category->icon)
                            <span class="mr-3 text-primary-600">{!! $category->icon !!}</span>
                        @endif
                        {{ $category->name }}
                    </h2>
                    
                    @if($category->description)
                        <p class="text-secondary-600 mb-6">{{ $category->description }}</p>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($category->activeServices as $service)
                            <x-card-service :service="$service" />
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
```

### resources/views/pages/services/show.blade.php

```html
@extends('layouts.app')

@section('meta_title', $service->meta_title ?? $service->name)
@section('meta_description', $service->meta_description ?? $service->short_description)

@section('content')
    <!-- Breadcrumbs -->
    <div class="bg-secondary-100 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-sm text-secondary-600">
                <a href="{{ route('home') }}" class="hover:text-primary-600">Главная</a>
                <span class="mx-2">/</span>
                <a href="{{ route('services.index') }}" class="hover:text-primary-600">Услуги</a>
                <span class="mx-2">/</span>
                <span class="text-secondary-900">{{ $service->name }}</span>
            </nav>
        </div>
    </div>

    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    @if($service->image)
                        <img 
                            src="{{ asset('storage/' . $service->image) }}" 
                            alt="{{ $service->name }}" 
                            class="w-full h-96 object-cover rounded-xl mb-8"
                        >
                    @endif
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-secondary-900 mb-6">{{ $service->name }}</h1>
                    
                    @if($service->full_description)
                        <div class="prose max-w-none text-secondary-700 mb-8">
                            {!! $service->full_description !!}
                        </div>
                    @endif
                    
                    @if($service->features && count($service->features) > 0)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-secondary-900 mb-4">Особенности услуги</h2>
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($service->features as $feature)
                                    <li class="flex items-center text-secondary-700">
                                        <svg class="w-5 h-5 text-primary-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if($service->gallery && count($service->gallery) > 0)
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-secondary-900 mb-4">Галерея</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($service->gallery as $image)
                                    <a href="{{ asset('storage/' . $image['image']) }}" class="block rounded-lg overflow-hidden hover:opacity-90 transition">
                                        <img src="{{ asset('storage/' . $image['image']) }}" alt="{{ $image['caption'] ?? '' }}" class="w-full h-48 object-cover">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-secondary-50 rounded-xl p-6 sticky top-24">
                        <div class="mb-6">
                            @if($service->price_from)
                                <p class="text-sm text-secondary-600 mb-1">Цена</p>
                                <p class="text-3xl font-bold text-primary-600">
                                    от {{ number_format($service->price_from, 0, ',', ' ') }} ₽
                                </p>
                                @if($service->price_to)
                                    <p class="text-sm text-secondary-500">до {{ number_format($service->price_to, 0, ',', ' ') }} ₽</p>
                                @endif
                            @endif
                        </div>
                        
                        @if($service->duration)
                            <div class="mb-6">
                                <p class="text-sm text-secondary-600 mb-1">Срок выполнения</p>
                                <p class="text-lg font-semibold text-secondary-900">{{ $service->duration }}</p>
                            </div>
                        @endif
                        
                        @if($service->area_from || $service->area_to)
                            <div class="mb-6">
                                <p class="text-sm text-secondary-600 mb-1">Площадь</p>
                                <p class="text-lg font-semibold text-secondary-900">
                                    {{ $service->area_from ? $service->area_from . ' м²' : '' }}
                                    {{ $service->area_from && $service->area_to ? ' - ' : '' }}
                                    {{ $service->area_to ? $service->area_to . ' м²' : '' }}
                                </p>
                            </div>
                        @endif
                        
                        <x-button href="{{ route('calculator') }}?service={{ $service->slug }}" class="w-full mb-3">
                            Рассчитать стоимость
                        </x-button>
                        
                        <x-button href="tel:{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('company_phone')) }}" variant="outline" class="w-full">
                            Позвонить
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
```

### resources/views/pages/portfolio/index.blade.php

```html
@extends('layouts.app')

@section('meta_title', 'Портфолио строительной компании Золотой Тур')
@section('meta_description', 'Примеры выполненных проектов: строительство домов, ремонт квартир, отделка помещений.')

@section('content')
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="section-title">Наши работы</h1>
                <p class="section-subtitle mx-auto">Реализованные проекты различной сложности</p>
            </div>
            
            <!-- Filter -->
            <div class="flex flex-wrap justify-center gap-3 mb-12" x-data="{ activeFilter: 'all' }">
                <button 
                    @click="activeFilter = 'all'"
                    :class="activeFilter === 'all' ? 'bg-primary-600 text-white' : 'bg-secondary-100 text-secondary-700 hover:bg-secondary-200'"
                    class="px-4 py-2 rounded-full font-medium transition"
                >
                    Все проекты
                </button>
                @foreach($services as $service)
                    <button 
                        @click="activeFilter = '{{ $service->slug }}'"
                        :class="activeFilter === '{{ $service->slug }}' ? 'bg-primary-600 text-white' : 'bg-secondary-100 text-secondary-700 hover:bg-secondary-200'"
                        class="px-4 py-2 rounded-full font-medium transition"
                    >
                        {{ $service->name }}
                    </button>
                @endforeach
            </div>
            
            <!-- Portfolio Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($portfolio as $item)
                    <x-portfolio-card :item="$item" />
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                {{ $portfolio->links() }}
            </div>
        </div>
    </section>
@endsection
```

### resources/views/pages/about.blade.php

```html
@extends('layouts.app')

@section('meta_title', 'О компании Золотой Тур — Строительная компания')
@section('meta_description', 'История компании, наша команда, преимущества работы с нами.')

@section('content')
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="section-title">О компании</h1>
                <p class="section-subtitle mx-auto">Профессиональный подход к каждому проекту</p>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16">
                <div class="text-center">
                    <div class="text-4xl font-bold text-primary-600 mb-2">15+</div>
                    <div class="text-secondary-600">лет на рынке</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-primary-600 mb-2">500+</div>
                    <div class="text-secondary-600">выполненных проектов</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-primary-600 mb-2">50+</div>
                    <div class="text-secondary-600">специалистов</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-primary-600 mb-2">10</div>
                    <div class="text-secondary-600">лет гарантии</div>
                </div>
            </div>
            
            <!-- Team -->
            @if($team->count() > 0)
                <div class="mb-16">
                    <h2 class="text-3xl font-bold text-secondary-900 text-center mb-12">Наша команда</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($team as $member)
                            <div class="text-center">
                                @if($member->photo)
                                    <img 
                                        src="{{ asset('storage/' . $member->photo) }}" 
                                        alt="{{ $member->full_name }}" 
                                        class="w-32 h-32 rounded-full object-cover mx-auto mb-4"
                                    >
                                @else
                                    <div class="w-32 h-32 rounded-full bg-primary-100 flex items-center justify-center mx-auto mb-4">
                                        <span class="text-3xl font-bold text-primary-600">
                                            {{ collect(explode(' ', $member->full_name))->map(fn($n) => mb_substr($n, 0, 1))->join('') }}
                                        </span>
                                    </div>
                                @endif
                                <h3 class="text-lg font-bold text-secondary-900">{{ $member->full_name }}</h3>
                                <p class="text-primary-600">{{ $member->position }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
```

### resources/views/pages/contacts.blade.php

```html
@extends('layouts.app')

@section('meta_title', 'Контакты Золотой Тур')
@section('meta_description', 'Адрес, телефон, email строительной компании Золотой Тур. Свяжитесь с нами.')

@section('content')
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="section-title">Контакты</h1>
                <p class="section-subtitle mx-auto">Свяжитесь с нами удобным способом</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-secondary-900">Телефон</h3>
                                <a href="tel:{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('company_phone')) }}" class="text-secondary-600 hover:text-primary-600">
                                    {{ \App\Models\Setting::get('company_phone') }}
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-secondary-900">Email</h3>
                                <a href="mailto:{{ \App\Models\Setting::get('company_email') }}" class="text-secondary-600 hover:text-primary-600">
                                    {{ \App\Models\Setting::get('company_email') }}
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-secondary-900">Адрес</h3>
                                <p class="text-secondary-600">{{ \App\Models\Setting::get('company_address') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-secondary-900">Часы работы</h3>
                                <p class="text-secondary-600">{{ \App\Models\Setting::get('company_work_hours') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="bg-secondary-50 rounded-xl p-6 lg:p-8">
                    <h2 class="text-2xl font-bold text-secondary-900 mb-6">Написать нам</h2>
                    <x-lead-form />
                </div>
            </div>
        </div>
    </section>
@endsection
```

### resources/views/pages/calculator.blade.php

```html
@extends('layouts.app')

@section('meta_title', 'Калькулятор стоимости строительства и ремонта')
@section('meta_description', 'Рассчитайте примерную стоимость строительных и отделочных работ онлайн.')

@push('scripts')
    <script>
        function calculator() {
            return {
                serviceId: '{{ request('service') }}',
                area: 50,
                options: [],
                result: null,
                loading: false,
                
                services: @json($services),
                
                get selectedService() {
                    return this.services.find(s => s.slug === this.serviceId);
                },
                
                get basePrice() {
                    if (!this.selectedService) return 0;
                    const pricePerM2 = this.selectedService.price_from / (this.selectedService.area_from || 1);
                    return pricePerM2 * this.area;
                },
                
                get optionsPrice() {
                    // Логика расчёта доп. опций
                    return this.options.length * 5000;
                },
                
                get totalPrice() {
                    return this.basePrice + this.optionsPrice;
                },
                
                calculate() {
                    this.loading = true;
                    // AJAX запрос для точного расчёта
                    fetch('{{ route('calculator.calculate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            service_id: this.selectedService?.id,
                            area: this.area,
                            options: this.options
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.result = data;
                        this.loading = false;
                    });
                },
                
                formatPrice(price) {
                    return new Intl.NumberFormat('ru-RU').format(price);
                }
            }
        }
    </script>
@endpush

@section('content')
    <section class="py-16 lg:py-24" x-data="calculator()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="section-title">Калькулятор стоимости</h1>
                <p class="section-subtitle mx-auto">Рассчитайте примерную стоимость работ онлайн</p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8">
                    <!-- Step 1: Service -->
                    <div class="mb-8">
                        <label class="block text-lg font-semibold text-secondary-900 mb-4">1. Выберите услугу</label>
                        <select 
                            x-model="serviceId" 
                            class="w-full px-4 py-3 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        >
                            <option value="">-- Выберите услугу --</option>
                            @foreach($serviceCategories as $category)
                                <optgroup label="{{ $category->name }}">
                                    @foreach($category->services as $service)
                                        <option value="{{ $service->slug }}">{{ $service->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Step 2: Area -->
                    <div class="mb-8" x-show="selectedService" x-transition>
                        <label class="block text-lg font-semibold text-secondary-900 mb-4">
                            2. Укажите площадь (м²)
                        </label>
                        <input 
                            type="range" 
                            x-model="area" 
                            min="10" 
                            max="500" 
                            step="5"
                            class="w-full mb-2"
                        >
                        <div class="flex justify-between text-sm text-secondary-600">
                            <span>10 м²</span>
                            <span class="text-lg font-bold text-primary-600" x-text="area + ' м²'"></span>
                            <span>500 м²</span>
                        </div>
                    </div>
                    
                    <!-- Step 3: Options -->
                    <div class="mb-8" x-show="selectedService" x-transition>
                        <label class="block text-lg font-semibold text-secondary-900 mb-4">3. Дополнительные опции</label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" x-model="options" value="materials" class="w-5 h-5 text-primary-600 rounded focus:ring-primary-500">
                                <span class="ml-3 text-secondary-700">Закупка материалов</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" x-model="options" value="design" class="w-5 h-5 text-primary-600 rounded focus:ring-primary-500">
                                <span class="ml-3 text-secondary-700">Дизайн-проект</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" x-model="options" value="urgent" class="w-5 h-5 text-primary-600 rounded focus:ring-primary-500">
                                <span class="ml-3 text-secondary-700">Срочный заказ</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Result -->
                    <div class="bg-secondary-50 rounded-lg p-6 mb-6" x-show="selectedService">
                        <div class="text-center">
                            <p class="text-secondary-600 mb-2">Примерная стоимость:</p>
                            <p class="text-4xl font-bold text-primary-600" x-text="formatPrice(totalPrice) + ' ₽'"></p>
                            <p class="text-sm text-secondary-500 mt-2">* Точная стоимость рассчитывается после осмотра</p>
                        </div>
                    </div>
                    
                    <!-- Lead Form -->
                    <div x-show="selectedService" x-transition>
                        <h3 class="text-lg font-semibold text-secondary-900 mb-4">Получить точный расчёт</h3>
                        <x-lead-form :source="'calculator'" />
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
```
