<?php

namespace YoAuth\Http\Actions;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use YoAuth\YoAuth;

class Action
{
    private static $client;

    private static $token;

    public function __construct()
    {
        self::$token = YoAuth::token();
    }

    public static function client()
    {
        if (!self::$client instanceof Client || self::$token !== YoAuth::token()) {
            $headers = [
                'Accept' => 'application/json'
            ];

            if (!empty(YoAuth::token())) {
                $headers = $headers + YoAuth::authHeader();
            }

            self::$client = new Client([
                'base_uri' => config('yoauth.base_uri'),
                'headers' => $headers
            ]);
        }

        return self::$client;
    }
}
