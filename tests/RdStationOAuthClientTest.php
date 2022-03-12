<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Pedroni\RdStation\RdStationOAuthClient;

it('retrieves tokens', function () {
    Http::fake([
        '*auth/token*' => Http::response([
            'access_token' => 'TEST_ACCESS_TOKEN',
            'refresh_token' =>
            'TEST_REFRESH_TOKEN',
            'expires_in' => 3600,
        ], 200),
    ]);

    /** @var RdStationOAuthClient */
    $client = app()->make(RdStationOAuthClient::class);

    expect($client->retrieveTokens('TEST_CODE'))
        ->accessToken()->toBe('TEST_ACCESS_TOKEN')
        ->refreshToken()->toBe('TEST_REFRESH_TOKEN')
        ->expiresIn()->toBe(3600);

    Http::assertSentCount(1);

    Http::assertSent(function (Request $request) {
        expect($request->data())->toBe([
            'client_id' => 'TEST_CLIENT_ID',
            'client_secret' => 'TEST_CLIENT_SECRET',
            'code' => 'TEST_CODE',
        ]);

        expect($request->method())->toBe('POST');

        return true;
    });
});
