<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Pedroni\RdStation\Repositories\EventRepository;

test('conversion', function () {
    $this->mockConfig();

    Http::fake();

    /** @var EventRepository */
    $repository = app()->make(EventRepository::class);
    $repository->conversion([]);

    // This is used so we can use expectations on the
    // assertSent instead of returning a boolean
    Http::assertSentCount(1);

    Http::assertSent(
        fn (Request $request) =>
        expect($request)
            ->data()->toMatchArray([
                'event_type' => 'CONVERSION',
                'event_family' => 'CDP',
                'payload' => [],
            ])
            ->url()->toBe('https://api.rd.services/platform/events')
    );
});
test('batch conversions', function () {
    $this->mockConfig();

    Http::fake();

    /** @var EventRepository */
    $repository = app()->make(EventRepository::class);
    $repository->batchConversions([
        [
            'email' => 'example@mail.com',
        ],
        [
            'email' => 'another@mail.com',
        ],
    ]);

    // This is used so we can use expectations on the
    // assertSent instead of returning a boolean
    Http::assertSentCount(1);

    Http::assertSent(
        fn (Request $request) =>
        expect($request)
            ->method()->toBe('POST')
            ->url()->toBe('https://api.rd.services/platform/events/batch')

            ->data()->toMatchArray([
                [
                    'event_type' => 'CONVERSION',
                    'event_family' => 'CDP',
                    'payload' => [
                        'email' => 'example@mail.com',
                    ],
                ],
                [
                    'event_type' => 'CONVERSION',
                    'event_family' => 'CDP',
                    'payload' => [
                        'email' => 'another@mail.com',
                    ],
                ],
            ])
    );
});
