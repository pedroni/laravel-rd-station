<?php

namespace Pedroni\RdStation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Pedroni\RdStation\RdStation
 * @method static \Pedroni\RdStation\Repositories\ContactRepository contacts()
 * @method static \Pedroni\RdStation\Repositories\EventRepository events()
 */
class RdStation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rd-station';
    }
}
