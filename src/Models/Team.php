<?php

declare(strict_types=1);

namespace YoAuth\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use function sprintf;

class Team extends Model
{
    protected $fillable = ['id', 'name', 'created_at', 'updated_at'];

    public static function make(int $id, string $name, string $created_at, string $updated_at)
    {
        $cacheKey = sprintf('team.%s', $id);

        return Cache::remember($cacheKey, 3360, static function () use ($id, $name, $created_at, $updated_at) {
            return new static([
                'id'         => $id,
                'name'       => $name,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        });
    }

    public static function findOrFail(int $id): Team
    {
        $cacheKey = sprintf('team.%s', $id);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        throw (new ModelNotFoundException())->setModel(__CLASS__, $id);
    }
}
