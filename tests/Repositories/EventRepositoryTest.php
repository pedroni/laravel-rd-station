<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Pedroni\RdStation\Repositories\EventRepository;

test('conversion', function () {
    Http::fake();

    /** @var EventRepository */
    $repository = app()->make(EventRepository::class);
    $repository->conversion([]);

    // This is used so we can use expectations on the
    // assertSent instead of returning a boolean
    Http::assertSentCount(1);

    Http::assertSent(function (Request $request) {
        expect($request->data())
            ->toMatchArray([
                'event_type' => 'CONVERSION',
                'event_family' => 'CDP',
                'payload' => [],
            ]);

        expect($request->url())
            ->toBe('https://api.rd.services/platform/conversions?api_key=TEST_API_KEY');

        return true;
    });
});
