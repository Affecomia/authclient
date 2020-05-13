<?php

namespace YoAuth\Http\Actions;

use YoAuth\Http\Requests\ShowUser as ShowUserRequest;
use YoAuth\YoAuth;

class ShowUser extends Action
{
    public function __invoke(ShowUserRequest $request, $userId = 'me')
    {
        $response = self::client()->get(sprintf('users/%s', $userId));

        return response($response->getBody()->getContents(), $response->getStatusCode());
    }
}
