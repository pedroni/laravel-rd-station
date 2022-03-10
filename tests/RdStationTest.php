<?php

use Pedroni\RdStation\Facades\RdStation as RdStationFacade;
use Pedroni\RdStation\RdStation;
use Pedroni\RdStation\Repositories\ContactRepository;

it('can access the contacts repository using facade')
    ->expect(fn () => RdStationFacade::contacts())
    ->toBeInstanceOf(ContactRepository::class);

it('can access the contacts repository using dependency injection')
    ->expect(fn () => app()->make(RdStation::class)->contacts())
    ->toBeInstanceOf(ContactRepository::class);
