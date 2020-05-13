<?php

namespace YoAuth\Traits;

use Ramsey\Uuid\Uuid;

trait Uuids
{
    /**
     * Boot function from laravel
     */
    protected static function bootUuids()
    {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::uuid4();
        });
    }

    /**
     * {@see \Illuminate\Database\Eloquent\Model::getIncrementing()}
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * {@see \Illuminate\Database\Eloquent\Model::getKeyType()}
     *
     * @return bool
     */
    public function getKeyType()
    {
        return 'string';
    }
}
