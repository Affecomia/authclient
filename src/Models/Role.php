<?php

declare(strict_types=1);

namespace YoAuth\Models;

use Illuminate\Support\Facades\Cache;
use YoAuth\YoAuth;
use function array_map;
use function json_decode;
use function sprintf;

class Role extends Model
{
    protected $fillable = [
        'id',
        'name',
        'display_name',
        'team_id',
    ];

    public static function make(int $id, string $name, string $display_name, ?int $team_id)
    {
        $cacheKey = sprintf('role.%s', $id);

        return Cache::remember($cacheKey, 3360, static function () use ($id, $name, $display_name, $team_id) {
            return new static([
                'id'         => $id,
                'name'       => $name,
                'display_name' => $display_name,
                'team_id' => $team_id,
            ]);
        });
    }

    public function getPermissions() : array
    {
        $cacheKey = sprintf('role.permissions.%s', $this->id);

        return Cache::remember($cacheKey, 3360, function () {
            $response = $this->client->get(sprintf('/roles/%s', $this->id), [
                'headers' => YoAuth::authHeader(),
            ]);

            $responseArray = json_decode($response->getBody()->getContents(), true);

            return array_map(static function ($role) {
                return Role::make($role['id'], $role['name'], $role['display_name'], $role['team_id']);
            }, $responseArray);
        });
    }
}
