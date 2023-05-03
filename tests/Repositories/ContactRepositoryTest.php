<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Pedroni\RdStation\Repositories\ContactRepository;

function contactRepository(): ContactRepository
{
    return app()->make(ContactRepository::class);
}

test('update contact', function () {
    $this->mockConfig();

    Http::fake([
        '*platform/contacts/email:email@example.com' => Http::response([], 200),
    ]);

    contactRepository()
        ->update('email@example.com', [
            'name' => 'John Doe',
        ]);

    Http::assertSentCount(1);

    Http::assertSent(
        fn (
            Request $request
        ) =>
        expect($request)
            ->method()->toBe('PATCH')
            ->url()->toBe('https://api.rd.services/platform/contacts/email:email@example.com')
            ->data()->toBe([
                'name' => 'John Doe',
            ])
    );
});

test('find contact', function () {
    $this->mockConfig();

    Http::fake([
        '*platform/contacts/email:email@example.com' => Http::response([
            'email' => 'email@example.com',
            'name' => 'John Doe',
            'job_title' => 'CEO',
        ], 200),
    ]);

    $contact = contactRepository()
        ->find('email@example.com', );

    expect($contact)->toBe([
        'email' => 'email@example.com',
        'name' => 'John Doe',
        'job_title' => 'CEO',
    ]);

    Http::assertSentCount(1);

    Http::assertSent(
        fn (
            Request $request
        ) =>
        expect($request)
            ->method()->toBe('GET')
            ->url()->toBe('https://api.rd.services/platform/contacts/email:email@example.com')
    );
});

test('deletes contact', function (array $body, int $status, int $count) {
    $this->mockConfig();

    Http::fake([
        '*platform/contacts/email:email@example.com' => Http::response($body, $status),
    ]);


    // we consider 404 a succesfull status code because ,
    // if could not delete then it's already deleted
    if ($status === 404 || $status >= 200 && $status < 400) {
        contactRepository()->delete('email@example.com');
    } else {
        expect(
            fn () => contactRepository()->delete('email@example.com')
        )
            ->toThrow(RequestException::class);
    }

    Http::assertSentCount($count);

    Http::assertSent(
        fn (
            Request $request
        ) =>
        expect($request)
            ->method()->toBe('DELETE')
            ->url()->toBe('https://api.rd.services/platform/contacts/email:email@example.com')
    );
})->with([
    [[], 204, 1],
    [[], 404, 3],
    [[], 500, 3],
]);

test('sync tags on contact', function () {
    $this->mockConfig();

    Http::fake([
        '*platform/contacts/email:email@example.com/tag' => Http::response([], 200),
    ]);

    contactRepository()
        ->syncTags('email@example.com', ['example-tag', 'another-tag']);

    Http::assertSentCount(1);

    Http::assertSent(
        fn (
            Request $request
        ) =>
        expect($request)
            ->method()->toBe('POST')
            ->url()->toBe('https://api.rd.services/platform/contacts/email:email@example.com/tag')
            ->data()->toBe([
                'tags' => ['example-tag', 'another-tag'],
            ])
    );
});
