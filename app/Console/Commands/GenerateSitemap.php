<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml file';

    public function handle(): int
    {
        $services = Service::active()->get();
        $categories = ServiceCategory::query()->where('is_active', true)->get();
        $portfolio = Portfolio::active()->get();
        $pages = Page::query()->where('is_active', true)->get();

        $sitemap = view('sitemap', compact('services', 'categories', 'portfolio', 'pages'))->render();

        Storage::disk('public')->put('sitemap.xml', $sitemap);
        file_put_contents(public_path('sitemap.xml'), $sitemap);

        $this->info('Sitemap generated successfully.');

        return self::SUCCESS;
    }
}
