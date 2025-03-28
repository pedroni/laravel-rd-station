<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Pedroni\RdStation\RdStationOAuthClient;
use Pedroni\RdStation\Support\RdStationConfig;

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

    expect($client->retrieveTokens('generate', 'TEST_CODE'))
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

it('refreshes token when expired', function () {
    Carbon::setTestNow($testNow = new Carbon('Y-m-d H:i:s', '2022-03-11T12:00:00Z', 'UTC'));

    $this->mockConfig();

    Carbon::setTestNow($testNow->addHour()); // jump one hour in time

    Http::fake([
        '*auth/token*' => Http::response([
            'access_token' => 'TEST_ACCESS_TOKEN_REFRESHED',
            'refresh_token' =>
            'TEST_REFRESH_TOKEN',
            'expires_in' => 3600,
        ], 200),
    ]);

    /** @var RdStationOAuthClient */
    $client = app()->make(RdStationOAuthClient::class);

    /** @var RdStationConfig */
    $config = app()->make(RdStationConfig::class);

    expect($config)
        ->accessToken()->toBe('TEST_ACCESS_TOKEN')
        ->expiresAt()->format('Y-m-d H:i:s')->toBe('2022-03-11 13:00:00') // note that we are still at 1pm
        ->isExpired()->toBe(true);

    // this should call refresh token internally so it can use the token
    $client->withToken();

    expect($config)
        ->accessToken()->toBe('TEST_ACCESS_TOKEN_REFRESHED')
        ->expiresAt()->format('Y-m-d H:i:s')->toBe('2022-03-11 14:00:00') // note that now its 2pm
        ->isExpired()->toBe(true);

    Http::assertSentCount(1);

    Http::assertSent(function (Request $request) {
        expect($request)
            ->data()->toBe([
                'client_id' => 'TEST_CLIENT_ID',
                'client_secret' => 'TEST_CLIENT_SECRET',
                'refresh_token' => 'TEST_REFRESH_TOKEN',
            ])
            ->method()->toBe('POST');

        return true;
    });
});

it('cant retrieve tokens with an invalid strategy', function () {
    $this->mockConfig();

    /** @var RdStationOAuthClient */
    $client = app()->make(RdStationOAuthClient::class);

    expect(fn () => $client->retrieveTokens('WRONG_STRATEGY', 'TEST_VALUE'))
        ->toThrow(
            InvalidArgumentException::class,
            'RdStationOAuthClient::retrieveTokens $strategy argument was invalid found `WRONG_STRATEGY` but the only strategies found are `refresh` or `generate`.'
        );
});

it('cant refresh access token without refresh token', function () {
    /** @var RdStationOAuthClient */
    $client = app()->make(RdStationOAuthClient::class);

    expect(fn () => $client->refreshAccessToken())
        ->toThrow(
            RuntimeException::class,
            'Cannot refresh access token without first installing the integration.'
        );
});
