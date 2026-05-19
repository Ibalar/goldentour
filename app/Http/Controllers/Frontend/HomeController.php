<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $services = Service::active()
            ->showOnHome()
            ->with('category')
            ->orderBy('name')
            ->take(8)
            ->get();

        $servicesTotal = Service::active()->showOnHome()->count();

        $portfolio = Portfolio::active()
            ->featured()
            ->with('service')
            ->latest('completion_date')
            ->take(6)
            ->get();

        $reviews = Review::published()
            ->verified()
            ->with('service')
            ->latest()
            ->take(3)
            ->get();

        $categories = ServiceCategory::query()
            ->where('is_active', true)
            ->withCount(['activeServices as active_services_count'])
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $team = TeamMember::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $stats = [
            'years' => 15,
            'projects' => Portfolio::active()->count(),
            'services' => Service::active()->count(),
            'reviews' => Review::published()->count(),
        ];

        return view('pages.home', compact('services', 'servicesTotal', 'portfolio', 'reviews', 'categories', 'team', 'stats'));
    }
}
