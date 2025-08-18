<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Translation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'translator';
    }

    public static function get($key, array $replace = [], $locale = null)
    {
        return static::getFacadeRoot()->get($key, $replace, $locale);
    }
}
