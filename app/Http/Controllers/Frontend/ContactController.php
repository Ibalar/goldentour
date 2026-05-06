<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Portfolio;
use App\Models\Review;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('pages.contacts');
    }

    public function about(): View
    {
        $team = TeamMember::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $reviews = Review::published()
            ->verified()
            ->with('service')
            ->latest()
            ->take(3)
            ->get();

        $stats = [
            'years' => 15,
            'projects' => Portfolio::active()->count(),
            'services' => Service::active()->count(),
            'reviews' => Review::published()->count(),
        ];

        return view('pages.about', compact('team', 'reviews', 'stats'));
    }

    public function page(string $slug): View
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.dynamic', compact('page'));
    }
}
