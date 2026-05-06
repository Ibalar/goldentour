<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Contracts\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $categories = ServiceCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['activeServices' => fn ($query) => $query->orderBy('name')])
            ->get();

        return view('pages.services.index', compact('categories'));
    }

    public function show(Service $service): View
    {
        $service->load(['category', 'reviews' => fn ($query) => $query->published()->verified()->latest()->take(3)]);

        $relatedServices = Service::active()
            ->where('category_id', $service->category_id)
            ->whereKeyNot($service->getKey())
            ->orderBy('name')
            ->take(3)
            ->get();

        return view('pages.services.show', compact('service', 'relatedServices'));
    }

    public function category(string $slug): View
    {
        $category = ServiceCategory::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['activeServices' => fn ($query) => $query->orderBy('name')])
            ->firstOrFail();

        return view('pages.services.category', compact('category'));
    }
}
