<?php

declare(strict_types=1);

namespace YoAuth\Access\Checkers;

use Illuminate\Support\Facades\Cache;
use YoAuth\Checkers\CheckerContract;
use YoAuth\Models\Team;
use YoAuth\YoAuth;
use function array_map;
use function json_decode;
use function sprintf;

class TeamChecker extends CheckerContract
{
    public function getForCurrentUser() : array
    {
        $cacheKey = sprintf('acl.teams.%s', YoAuth::token());

        return Cache::remember($cacheKey, 3360, function () {
            $response = $this->client->get('/teams', [
                'headers' => YoAuth::authHeader(),
            ]);

            $responseArray = json_decode($response->getBody()->getContents(), true);

            return array_map(static function ($team) {
                return Team::make($team['id'], $team['name'], $team['created_at'], $team['updated_at']);
            }, $responseArray);
        });
    }

    public function currentUserHas(string $toCheck, Team $team) : bool
    {
        return false;
    }
}
