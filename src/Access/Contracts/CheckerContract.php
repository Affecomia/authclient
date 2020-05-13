<?php

declare(strict_types=1);

namespace YoAuth\Checkers;

use GuzzleHttp\Client;
use YoAuth\Models\Team;

abstract class CheckerContract
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('yoauth.base_uri'),
        ]);
    }

    abstract public function getForCurrentUser() : array;

    abstract public function currentUserHas(string $toCheck, Team $team) : bool;
}
