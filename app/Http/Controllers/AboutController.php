<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function __invoke(): Response
    {
        $teamMembers = Cache::remember('team_members', 3600, function () {
            return TeamMember::active()->ordered()->get();
        });

        return Inertia::render('About', [
            'teamMembers' => $teamMembers,
        ]);
    }
}
