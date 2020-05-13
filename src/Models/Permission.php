<?php

declare(strict_types=1);

namespace YoAuth\Models;

use Illuminate\Support\Facades\Cache;
use function sprintf;

class Permission extends Model
{
    protected $fillable = [
        'id',
        'name',
        'display_name',
        'team_id',
    ];

    public static function make(int $id, string $name, string $display_name, ?int $team_id) : Permission
    {
        $cacheKey = sprintf('permission.%s', $id);

        return Cache::remember($cacheKey, 3360, static function () use ($id, $name, $display_name, $team_id) {
            return new static([
                'id'         => $id,
                'name'       => $name,
                'display_name' => $display_name,
                'team_id' => $team_id,
            ]);
        });
    }
}
