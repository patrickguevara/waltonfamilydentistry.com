<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $services = Cache::remember('services', 3600, function () {
            return Service::active()->ordered()->limit(3)->get();
        });

        $teamMembers = Cache::remember('team_members', 3600, function () {
            return TeamMember::active()->ordered()->get();
        });

        return Inertia::render('Home', [
            'services' => $services,
            'teamMembers' => $teamMembers,
        ]);
    }
}
