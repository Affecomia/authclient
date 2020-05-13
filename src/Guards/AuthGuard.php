<?php

namespace YoAuth\Guards;

use GuzzleHttp\Client;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use YoAuth\Models\User;

class AuthGuard implements Guard
{
    /**
     * @var Authenticatable
     */
    protected $user;

    /**
     * @var UserProvider
     */
    protected $provider;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Client
     */
    private $client;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;

        $this->client = new Client([
            'base_uri' => config('yoauth.base_uri')
        ]);
    }

    public function guest(): bool
    {
        return !$this->check();
    }

    public function check(): bool
    {
        $auth_header = $this->request->header('Authorization');

        if (false !== strpos($auth_header, 'Bearer')) {
            if (Cache::has($auth_header)) {
                $this->user = Cache::get($auth_header);
            } else {
                try {
                    // Get user data
                    $user_response = $this->client->get('users/me', [
                        'headers' => [
                            'Authorization' => $auth_header,
                            'Accept' => 'application/json'
                        ]
                    ]);

                    $contents = \GuzzleHttp\json_decode($user_response->getBody()->getContents());
                    $user = $contents->data;

                } catch (\Exception $e) {
                    return false;
                }

                // Find user
                $this->user = new User([
                    'id' => $user->id,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                ]);

                Cache::put($auth_header, $this->user, 60);
            }
        }

        return !is_null($this->user());
    }

    public function user()
    {
        return $this->user;
    }

    public function id()
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }

        return null;
    }

    public function validate(array $credentials = []): bool
    {
        if (empty($credentials['username']) || empty($credentials['password'])) {
            return false;
        }

        $user = $this->provider->retrieveByCredentials($credentials);

        if (!is_null($user) && $this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);

            return true;
        } else {
            return false;
        }
    }

    public function setUser(Authenticatable $user): self
    {
        $this->user = $user;

        return $this;
    }
}
