<?php

namespace Pedroni\RdStation\Facades;

use Illuminate\Support\Facades\Facade;
use Pedroni\RdStation\Repositories\ContactRepository;

/**
 * @see \Pedroni\RdStation\RdStation
 * @method static ContactRepository contacts()
 */
class RdStation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rd-station';
    }
}
