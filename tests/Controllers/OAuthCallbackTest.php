<?php

use Pedroni\RdStation\RdStationOAuthClient;
use Pedroni\RdStation\Support\Definitions\RetrieveTokensResponse;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\getJson;
use function Pest\Laravel\mock;

it('retrieves tokens and persist it on database', function () {
    $oneHourInSeconds = 3600;
    mock(RdStationOAuthClient::class)
        ->shouldReceive('retrieveTokens')
        ->once()
        ->andReturn(
            new RetrieveTokensResponse(
                'TEST_ACCESS_TOKEN',
                'TEST_REFRESH_TOKEN',
                $oneHourInSeconds
            )
        );

    assertDatabaseCount('rd_station_config', 0);

    getJson('rd-station/oauth/callback?code=example')
        ->dump()
        ->assertSuccessful()
        ->assertJson(['message' => 'retrieved tokens successfully']);
});
