<?php

namespace App\Console\Commands;

use App\Models\HomeMatchNotification;
use App\Models\User;
use App\Notifications\UpcomingHomeMatchNotification;
use App\Services\ApiFootballService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class NotifyFavoriteHomeMatches extends Command
{
    protected $signature = 'favorites:notify-home-matches';
    protected $description = 'Invia email quando una squadra di uno stadio preferito gioca in casa';

    public function handle(ApiFootballService $apiFootballService): int
    {
        $users = User::with('favorites')->get();

        foreach ($users as $user) {
            foreach ($user->favorites as $stadium) {
                $fixtures = $apiFootballService->upcomingFixturesForTeam($stadium->team_id);

                foreach ($fixtures as $fixture) {
                    $isHome = (int) data_get($fixture, 'teams.home.id') === (int) $stadium->team_id;
                    if (! $isHome) {
                        continue;
                    }

                    $fixtureId = (string) data_get($fixture, 'fixture.id');
                    $matchDate = Carbon::parse(data_get($fixture, 'fixture.date'));

                    if ($matchDate->isPast()) {
                        continue;
                    }

                    $alreadySent = HomeMatchNotification::where('user_id', $user->id)
                        ->where('fixture_id', $fixtureId)
                        ->exists();

                    if ($alreadySent) {
                        continue;
                    }

                    $opponentName = data_get($fixture, 'teams.away.name', 'Squadra avversaria');

                    $user->notify(new UpcomingHomeMatchNotification(
                        $stadium->team_name,
                        $stadium->name,
                        $opponentName,
                        $matchDate->format('d/m/Y H:i')
                    ));

                    HomeMatchNotification::create([
                        'user_id' => $user->id,
                        'stadium_id' => $stadium->id,
                        'fixture_id' => $fixtureId,
                        'match_date' => $matchDate,
                    ]);
                }
            }
        }

        $this->info('Notifiche controllate correttamente.');
        return self::SUCCESS;
    }
}