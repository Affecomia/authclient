<?php

declare(strict_types=1);

namespace YoAuth\Access\Checkers;

use Illuminate\Support\Facades\Cache;
use YoAuth\Checkers\CheckerContract;
use YoAuth\Models\Permission;
use YoAuth\Models\Team;
use YoAuth\YoAuth;
use function array_map;
use function json_decode;
use function sprintf;

class PermissionChecker extends CheckerContract
{
    public function getForCurrentUser() : array
    {
        $cacheKey = sprintf('acl.permissions.%s', YoAuth::token());

        return Cache::remember($cacheKey, 3360, function () {
            $response = $this->client->get('/permissions', [
                'headers' => YoAuth::authHeader(),
            ]);

            $responseArray = json_decode($response->getBody()->getContents(), true);
            $result = [];

            foreach ($responseArray as $teamRoles) {
                $result[] = array_map(static function ($permission) {
                    return Permission::make($permission['id'], $permission['name'], $permission['display_name'], $permission['team']);
                }, $teamRoles);
            }

            return $result;
        });
    }

    public function currentUserHas(string $toCheck, ?Team $team) : bool
    {
        foreach ($this->getForCurrentUser() as $permissionInTeam) {
            foreach ($permissionInTeam as $permission) {
                if ($permission['name'] === $toCheck && ($team === null || $permission['team_id'] === $team->id)) {
                    return true;
                }
            }
        }

        return false;
    }
}
