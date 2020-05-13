<?php

namespace YoAuth\Http\Actions;

use YoAuth\Http\Requests\Logout as LogoutRequest;

class Logout extends Action
{
    public function __invoke(LogoutRequest $request)
    {
        $response = self::client()->post('logout');

        return response($response->getBody()->getContents(), $response->getStatusCode());
    }
}