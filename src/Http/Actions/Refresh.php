<?php

namespace YoAuth\Http\Actions;

use Illuminate\Http\Request;
use YoAuth\Http\Requests\Refresh as RefreshRequest;

class Refresh extends Action
{
    public function __invoke(RefreshRequest $request)
    {
        $response = self::client()->post('oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->refresh_token,
                'client_id' => config('yoauth.client_id'),
                'client_secret' => config('yoauth.client_secret'),
            ]
        ]);

        return response($response->getBody()->getContents(), $response->getStatusCode());
    }
}