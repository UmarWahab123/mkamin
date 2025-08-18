<?php

namespace Filawidget\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Filawidget\Filawidget
 */
class Filawidget extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Filawidget\Filawidget::class;
    }
}
