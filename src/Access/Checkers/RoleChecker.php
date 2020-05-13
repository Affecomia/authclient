<?php

declare(strict_types=1);

namespace YoAuth\Access\Checkers;

use Illuminate\Support\Facades\Cache;
use YoAuth\Checkers\CheckerContract;
use YoAuth\Models\Role;
use YoAuth\Models\Team;
use YoAuth\YoAuth;
use function array_map;
use function json_decode;
use function sprintf;

class RoleChecker extends CheckerContract
{
    public function getForCurrentUser() : array
    {
        $cacheKey = sprintf('acl.roles.%s', YoAuth::token());

        return Cache::remember($cacheKey, 3360, function () {
            $response = $this->client->get('/roles', [
                'headers' => YoAuth::authHeader(),
            ]);

            $responseArray = json_decode($response->getBody()->getContents(), true);

            return array_map(static function ($role) {
                return Role::make($role['id'], $role['name'], $role['display_name'], $role['team_id']);
            }, $responseArray);
        });
    }

    public function currentUserHas(string $toCheck, ?Team $team = null) : bool
    {
        foreach ($this->getForCurrentUser() as $role) {
            if ($role['name'] === $toCheck && ($team === null || $role['team_id'] === $team->id)) {
                return true;
            }
        }

        return false;
    }
}
