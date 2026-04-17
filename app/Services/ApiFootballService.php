<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiFootballService
{
    public function upcomingFixturesForTeam(int $teamId): array
    {
        $response = Http::withHeaders([
            'x-rapidapi-key' => env('API_FOOTBALL_KEY'),
            'x-rapidapi-host' => 'v3.football.api-sports.io',
        ])->get(env('API_FOOTBALL_BASE_URL', 'https://v3.football.api-sports.io') . '/fixtures', [
            'team' => $teamId,
            'next' => 5,
        ]);

        if (! $response->successful()) {
            return [];
        }

        return $response->json('response', []);
    }
}
