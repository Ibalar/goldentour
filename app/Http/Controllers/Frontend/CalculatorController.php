<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index(): View
    {
        $services = Service::active()
            ->with('category')
            ->orderBy('name')
            ->get();

        $serviceCategories = ServiceCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['services' => fn ($query) => $query->where('is_active', true)->orderBy('name')])
            ->get();

        return view('pages.calculator', compact('services', 'serviceCategories'));
    }

    public function calculate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'area' => ['required', 'integer', 'min:10', 'max:1000'],
            'options' => ['nullable', 'array'],
        ]);

        $service = Service::query()->findOrFail($data['service_id']);
        $pricePerM2 = ($service->price_from ?? 0) / max($service->area_from ?: 1, 1);
        $basePrice = $pricePerM2 * $data['area'];

        $multiplier = 1;
        foreach ($data['options'] ?? [] as $option) {
            $multiplier += match ($option) {
                'materials' => 0.3,
                'design' => 0.15,
                'urgent' => 0.2,
                default => 0,
            };
        }

        return response()->json([
            'base_price' => round($basePrice, 2),
            'total_price' => round($basePrice * $multiplier, 2),
            'options_multiplier' => $multiplier,
            'service' => [
                'id' => $service->id,
                'name' => $service->name,
                'duration' => $service->duration,
            ],
        ]);
    }
}
