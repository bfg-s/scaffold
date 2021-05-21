<?php

namespace Bfg\Scaffold;

use Illuminate\Support\Facades\Facade as FacadeIlluminate;

/**
 * Class Facade
 * @package Bfg\Scaffold
 */
class Facade extends FacadeIlluminate
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Scaffold::class;
    }
}
