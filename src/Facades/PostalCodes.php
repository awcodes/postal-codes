<?php

namespace Awcodes\PostalCodes\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Awcodes\PostalCodes\PostalCodes
 */
class PostalCodes extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Awcodes\PostalCodes\PostalCodes::class;
    }
}
