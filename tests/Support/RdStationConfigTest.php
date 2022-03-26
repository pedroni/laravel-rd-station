<?php

declare(strict_types=1);

use Pedroni\RdStation\Support\RdStationConfig;

it('it is provided by the service provider')
    ->expect(fn () => app()->make(RdStationConfig::class))
    ->toBeInstanceOf(RdStationConfig::class);
