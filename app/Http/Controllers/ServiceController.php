<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        $services = Cache::remember('services', 3600, function () {
            return Service::active()->ordered()->get();
        });

        return Inertia::render('Services/Index', [
            'services' => $services,
        ]);
    }

    public function show(string $slug): Response
    {
        $service = Service::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedServices = Service::active()
            ->ordered()
            ->where('id', '!=', $service->id)
            ->limit(3)
            ->get();

        return Inertia::render('Services/Show', [
            'service' => $service,
            'relatedServices' => $relatedServices,
        ]);
    }
}
