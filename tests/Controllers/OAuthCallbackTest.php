<?php

use Carbon\Carbon;
use Pedroni\RdStation\RdStationOAuthClient;
use Pedroni\RdStation\Support\Definitions\RetrieveTokensResponse;
use Pedroni\RdStation\Support\RdStationConfig;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;
use function Pest\Laravel\mock;

it('retrieves tokens and persist it on database', function () {
    Carbon::setTestNow('2022-03-11 10:00:00');

    mock(RdStationOAuthClient::class)
        ->shouldReceive('retrieveTokens')
        ->with('TEST_CODE')
        ->once()
        ->andReturn(
            new RetrieveTokensResponse(
                'TEST_ACCESS_TOKEN',
                'TEST_REFRESH_TOKEN',
                $oneHourInSeconds = 3600
            )
        );

    assertDatabaseCount('rd_station_config', 0);

    getJson('rd-station/oauth/callback?code=TEST_CODE')
        ->assertSuccessful()
        ->assertJson(['message' => 'retrieved tokens successfully']);

    // make sure data was persisted
    assertDatabaseHas('rd_station_config', [
        'access_token' => 'TEST_ACCESS_TOKEN',
        'refresh_token' => 'TEST_REFRESH_TOKEN',
        'code' => 'TEST_CODE',
        'expires_at' => '2022-03-11 11:00:00' // one hour after the Carbon::setTestNow
    ]);

    /** @var RdStationConfig */
    $config = app()->make(RdStationConfig::class);

    // make sure data was updated in container instance
    expect($config)
        ->accessToken()->toBe('TEST_ACCESS_TOKEN')
        ->refreshToken()->toBe('TEST_REFRESH_TOKEN')
        ->code()->toBe('TEST_CODE')
        ->expiresAt()->format('Y-m-d H:i:s')->toBe('2022-03-11 11:00:00');
});

it('requires code on the query string', function () {

    getJson('rd-station/oauth/callback')
        ->assertStatus(400)
        ->assertJson(['message' => 'invalid code provided in the query string']);
});
