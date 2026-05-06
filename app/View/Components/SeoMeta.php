<?php

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
        public string $type = 'website',
    ) {
    }

    public function render(): View|Closure|string
    {
        $siteTitle = Setting::get('site_title', 'Золотой Тур');
        $defaultTitle = str_contains($siteTitle, 'Золотой Тур') ? $siteTitle : $siteTitle . ' | Золотой Тур';
        $resolvedTitle = $this->title ? (str_contains($this->title, 'Золотой Тур') ? $this->title : $this->title . ' | Золотой Тур') : $defaultTitle;
        $resolvedDescription = $this->description ?? Setting::get('site_description', 'Строительство и ремонт под ключ.');
        $defaultImage = asset('images/og-image.jpg');

        $resolvedImage = $this->image
            ? (str_starts_with($this->image, 'http') ? $this->image : asset('storage/' . ltrim($this->image, '/')))
            : $defaultImage;

        return view('components.seo-meta', [
            'title' => $resolvedTitle,
            'description' => $resolvedDescription,
            'keywords' => $this->keywords,
            'image' => $resolvedImage,
            'type' => $this->type,
            'url' => url()->current(),
        ]);
    }
}
