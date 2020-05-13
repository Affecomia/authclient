<?php

declare(strict_types=1);

namespace YoAuth\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use YoAuth\Traits\UserTrait;
use YoAuth\Traits\Uuids;

class User extends Model implements Authenticatable
{
    use Uuids;
    use UserTrait;

    protected $fillable = [
        'id',
        'email',
        'first_name',
        'last_name',
    ];

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value) : void
    {
        // TODO: Implement setRememberToken() method.
    }

    public function getRememberTokenName() : void
    {
        // TODO: Implement getRememberTokenName() method.
    }
}
