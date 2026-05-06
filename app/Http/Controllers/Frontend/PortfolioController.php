<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $portfolio = Portfolio::active()
            ->with('service')
            ->latest('completion_date')
            ->paginate(12);

        $services = Service::active()
            ->whereHas('portfolios', fn ($query) => $query->where('is_active', true))
            ->orderBy('name')
            ->get();

        return view('pages.portfolio.index', compact('portfolio', 'services'));
    }

    public function show(Portfolio $portfolio): View
    {
        $portfolio->load(['service', 'images']);

        $relatedProjects = Portfolio::active()
            ->where('service_id', $portfolio->service_id)
            ->whereKeyNot($portfolio->getKey())
            ->latest('completion_date')
            ->take(3)
            ->get();

        return view('pages.portfolio.show', compact('portfolio', 'relatedProjects'));
    }
}
