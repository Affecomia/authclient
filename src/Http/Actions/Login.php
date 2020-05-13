<?php

namespace YoAuth\Http\Actions;

use YoAuth\Http\Requests\Login as LoginRequest;
use YoAuth\Http\Actions\Action as BaseAction;

class Login extends BaseAction
{
    public function __invoke(LoginRequest $request)
    {
        $response = self::client()->post('oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('yoauth.client_id'),
                'client_secret' => config('yoauth.client_secret'),
                'username' => $request->username,
                'password' => $request->password,
            ]
        ]);

        return response($response->getBody()->getContents(), $response->getStatusCode());
    }
}