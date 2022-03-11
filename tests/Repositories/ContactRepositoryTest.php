<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Pedroni\RdStation\Exceptions\UnableToUpdateOrCreateEntity;
use Pedroni\RdStation\Repositories\ContactRepository;

test('update or create contact', function () {
    Http::fake();

    /** @var ContactRepository */
    $repository = app()->make(ContactRepository::class);
    $repository->updateOrCreate('email@example.com', []);

    Http::assertSent(
        fn (
            Request $request
        ) =>
        $request->url() === 'https://api.rd.services/platform/contacts/email:email@example.com?api_key=TEST_PRIVATE_TOKEN'
    );
})->skip();

test('throws unable to update or create contact on failure', function () {
    Http::fake([
        'https://api.rd.services/platform/contacts/email:email@example.com' => Http::response([
            'errors' => [
                'error_type' => 'RESOURCE_NOT_FOUND',
                'error_message' => 'Lead not found.',
            ],
        ], 404),
    ]);

    /** @var ContactRepository */
    $repository = app()->make(ContactRepository::class);

    expect(fn () => $repository->updateOrCreate('email@example.com', []))
        ->toThrow(UnableToUpdateOrCreateEntity::class);
})->skip();
