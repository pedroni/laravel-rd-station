<?php

namespace Pedroni\RdStation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Pedroni\RdStation\RdStation
 */
class RdStation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rd-station';
    }
}
