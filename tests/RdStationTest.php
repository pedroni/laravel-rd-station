<?php

declare(strict_types=1);

use Pedroni\RdStation\Facades\RdStation as RdStationFacade;
use Pedroni\RdStation\RdStation;
use Pedroni\RdStation\Repositories\ContactRepository;
use Pedroni\RdStation\Repositories\EventRepository;

// @todo refactor using dataprovider

it('can access the contacts repository using facade')
    ->expect(fn () => RdStationFacade::contacts())
    ->toBeInstanceOf(ContactRepository::class);

it('can access the events repository using facade')
    ->expect(fn () => RdStationFacade::events())
    ->toBeInstanceOf(EventRepository::class);

it('can access the contacts repository using dependency injection')
    ->expect(fn () => app()->make(RdStation::class)->contacts())
    ->toBeInstanceOf(ContactRepository::class);

it('can access the events repository using dependency injection')
    ->expect(fn () => app()->make(RdStation::class)->events())
    ->toBeInstanceOf(EventRepository::class);
