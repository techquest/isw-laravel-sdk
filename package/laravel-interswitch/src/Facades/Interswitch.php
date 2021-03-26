<?php
/**
 * This file is part of the Laravel Interswitch package
 * (c) Interswitch Group | 2021
 */
namespace Interswitch\Interswitch\Facades;

use Illuminate\Support\Facades\Facade;

class Interswitch extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-interswitch';
    }
}
