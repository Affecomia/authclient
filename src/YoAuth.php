<?php

namespace YoAuth;

use Illuminate\Support\Facades\Cache;
use YoAuth\Models\User;

class YoAuth
{
    public static function authHeader(): array
    {
        return [
            'Authorization' => sprintf('Bearer %s', self::token())
        ];
    }

    public static function token()
    {
        $authHeader = request()->header('Authorization');

        return str_replace('Bearer ', '', $authHeader);
    }

    public static function user(): ?User
    {
        return Cache::get(sprintf('Bearer %s', self::token()));
    }

    public static function id()
    {
        if (self::user() instanceof User) {
            return self::user()->id;
        }

        return null;
    }
}
