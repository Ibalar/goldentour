<?php

use App\Http\Controllers\Frontend\CalculatorController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LeadController;
use App\Http\Controllers\Frontend\PortfolioController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Models\Page;
use App\Models\Portfolio;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

Route::middleware(['utm.tracking', 'seo'])->group(function (): void {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::prefix('services')->name('services.')->group(function (): void {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/category/{slug}', [ServiceController::class, 'category'])->name('category');
        Route::get('/{service:slug}', [ServiceController::class, 'show'])->name('show');
    });

    Route::prefix('portfolio')->name('portfolio.')->group(function (): void {
        Route::get('/', [PortfolioController::class, 'index'])->name('index');
        Route::get('/{portfolio:slug}', [PortfolioController::class, 'show'])->name('show');
    });

    Route::prefix('calculator')->name('calculator.')->group(function (): void {
        Route::get('/', [CalculatorController::class, 'index'])->name('index');
        Route::post('/calculate', [CalculatorController::class, 'calculate'])->name('calculate');
    });

    Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator');
    Route::get('/about', [ContactController::class, 'about'])->name('about');
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');
    Route::get('/page/{slug}', [ContactController::class, 'page'])->name('page');
    Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
});

Route::get('/sitemap.xml', function () {
    $services = Service::active()->get();
    $categories = \App\Models\ServiceCategory::query()->where('is_active', true)->get();
    $portfolio = Portfolio::active()->get();
    $pages = Page::query()->where('is_active', true)->get();

    return response()
        ->view('sitemap', compact('services', 'categories', 'portfolio', 'pages'))
        ->header('Content-Type', 'text/xml');
});

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
        'Disallow: /admin/*',
        'Disallow: /storage',
        'Disallow: /vendor',
        'Disallow: /node_modules',
        'Disallow: /*?*utm_source=',
        'Disallow: /*?*utm_medium=',
        'Disallow: /*?*utm_campaign=',
        'Disallow: /*?*fbclid=',
        '',
        'Crawl-delay: 10',
    ];

    if (app()->environment('local', 'staging')) {
        $lines = [
            'User-agent: *',
            'Disallow: /',
        ];
    }

    return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
});
